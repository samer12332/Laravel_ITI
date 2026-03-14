<?php

use App\Jobs\PruneOldPostsJob;
use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

uses(RefreshDatabase::class);

it('deletes posts that are at least two years old when the job runs', function () {
    Carbon::setTestNow('2026-03-14 12:00:00');

    $user = User::factory()->create();

    $oldPost = Post::factory()->create([
        'user_id' => $user->id,
        'created_at' => now()->subYears(2)->subDay(),
    ]);

    $recentPost = Post::factory()->create([
        'user_id' => $user->id,
        'created_at' => now()->subMonths(6),
    ]);

    dispatch_sync(new PruneOldPostsJob());

    expect(Post::query()->find($oldPost->id))->toBeNull();
    expect(Post::query()->find($recentPost->id))->not->toBeNull();

    Carbon::setTestNow();
});

it('stores the prune job in the jobs table when using the database queue driver', function () {
    config()->set('queue.default', 'database');

    PruneOldPostsJob::dispatch();

    $payload = DB::table('jobs')->value('payload');
    $decodedPayload = json_decode($payload, true);

    expect($payload)->not->toBeNull();
    expect($decodedPayload['displayName'])->toBe(PruneOldPostsJob::class);
    expect($decodedPayload['data']['commandName'])->toBe(PruneOldPostsJob::class);
});
