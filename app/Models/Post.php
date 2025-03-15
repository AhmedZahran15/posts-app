<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;
use Cviebrock\EloquentSluggable\Sluggable;

class Post extends Model
{
    use HasFactory, SoftDeletes, Sluggable;

    // Add image to fillable attributes
    protected $fillable = ['title', 'description', 'user_id', 'slug', 'image'];

    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'title',
                'onUpdate' => true,
            ]
        ];
    }

    protected $appends = ['image_url'];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    // Image accessor - returns the URL for the image
    public function getImageUrlAttribute()
    {
        if ($this->image) {
            // Use asset() helper to generate a full URL with the correct domain
            return asset('storage/' . $this->image);
        }

        return null;
    }

    // Image mutator - handles image upload and old image deletion
    public function setImageAttribute($value)
    {
        if ($value && is_file($value)) {
            // Delete old image if exists
            $this->deleteImage();
            // Store new image with unique filename
            $filename = time() . '_' . $value->getClientOriginalName();
            $path = $value->storeAs('post-images', $filename, 'public');
            $this->attributes['image'] = $path;
        }
    }

    // Method to delete the image file from storage
    public function deleteImage()
    {
        if ($this->image && Storage::disk('public')->exists($this->image)) {
            Storage::disk('public')->delete($this->image);
        }
    }
}
