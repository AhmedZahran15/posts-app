<?php

namespace App\Observers;

use App\Models\Post;

class PostObserver
{
  /**
   * Handle the Post "deleted" event.
   */
  public function deleted(Post $post): void
  {
    // Only run if this is a soft delete (the deleted_at field will be set)
    if ($post->isForceDeleting()) {
      return;
    }

    // Cascade soft delete to all comments
    $post->comments()->delete();
  }

  /**
   * Handle the Post "restored" event.
   */
  public function restored(Post $post): void
  {
    // Cascade restore to all soft-deleted comments that belong to this post
    $post->comments()->onlyTrashed()->restore();
  }

  /**
   * Handle the Post "force deleted" event.
   */
  public function forceDeleted(Post $post): void
  {
    // Force delete all comments (including soft-deleted ones)
    $post->comments()->withTrashed()->forceDelete();
  }
}
