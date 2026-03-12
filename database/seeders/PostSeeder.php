<?php

namespace Database\Seeders;

use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Seeder;

class PostSeeder extends Seeder
{
    /**
     * Seed the application's database with posts.
     */
    public function run(): void
    {
        if (User::query()->count() === 0) {
            User::factory(20)->create();
        }

        $userIds = User::query()->pluck('id');

        Post::query()->delete();

        Post::factory(500)
            ->state(fn () => ['user_id' => $userIds->random()])
            ->create();
    }
}
