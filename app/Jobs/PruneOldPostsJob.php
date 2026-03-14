<?php

namespace App\Jobs;

use App\Models\Post;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class PruneOldPostsJob implements ShouldQueue
{
    use Queueable;

    public function handle(): void
    {
        Post::query()
            ->where('created_at', '<=', now()->subYears(2))
            ->delete();
    }
}
