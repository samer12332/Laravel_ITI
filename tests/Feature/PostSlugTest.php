<?php

use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

uses(RefreshDatabase::class);

it('generates a slug from the title when creating a post', function () {
    Storage::fake('public');

    $user = User::factory()->create();

    $response = $this->post(route('posts.store'), [
        'title' => 'Hello Laravel',
        'description' => 'This description is long enough.',
        'creator' => $user->id,
        'image' => uploadedSlugTestPng('post.png'),
        'slug' => 'custom-slug',
    ]);

    $response->assertRedirect(route('posts.index'));

    $post = Post::query()->latest('id')->first();

    expect($post)->not->toBeNull();
    expect($post->slug)->toBe('hello-laravel');
});

it('regenerates the slug from the title when updating a post', function () {
    Storage::fake('public');

    $user = User::factory()->create();
    $post = Post::query()->create([
        'title' => 'Old Title',
        'description' => 'This description is long enough.',
        'image' => uploadedSlugTestPng('old.png'),
        'user_id' => $user->id,
    ]);

    $response = $this->put(route('posts.update', $post->id), [
        'title' => 'Updated Laravel Title',
        'description' => 'This description is still long enough.',
        'creator' => $user->id,
        'slug' => 'hacked-slug',
    ]);

    $response->assertRedirect(route('posts.index'));

    expect($post->fresh()->slug)->toBe('updated-laravel-title');
});

function uploadedSlugTestPng(string $name): UploadedFile
{
    $path = tempnam(sys_get_temp_dir(), 'png');

    file_put_contents($path, base64_decode('iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mP8/x8AAwMCAO+aostAAAAASUVORK5CYII='));

    return new UploadedFile($path, $name, 'image/png', null, true);
}
