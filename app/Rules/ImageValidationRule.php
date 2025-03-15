<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Http\UploadedFile;

class ImageValidationRule implements ValidationRule
{
    /**
     * Run the validation rule.
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (!$value instanceof UploadedFile) {
            $fail('The file must be a valid image file.');
            return;
        }

        $extension = strtolower($value->getClientOriginalExtension());
        $mimeType = $value->getMimeType();

        $allowedExtensions = ['jpg', 'jpeg', 'png'];
        $allowedMimeTypes = ['image/jpeg', 'image/jpg', 'image/png'];

        if (!in_array($extension, $allowedExtensions)) {
            $fail("The {$attribute} must be a file of type: " . implode(', ', $allowedExtensions));
            return;
        }

        if (!in_array($mimeType, $allowedMimeTypes)) {
            $fail("The {$attribute} must be a valid image file. Detected mime type: {$mimeType}");
        }
    }
}
