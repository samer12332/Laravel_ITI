<?php

use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

uses(RefreshDatabase::class);

it('stores the uploaded post image on the public disk', function () {
    Storage::fake('public');

    $user = User::factory()->create();

    $response = $this->post(route('posts.store'), [
        'title' => 'Image Post',
        'description' => 'This description is long enough.',
        'creator' => $user->id,
        'image' => uploadedPng('cover.png'),
    ]);

    $response->assertRedirect(route('posts.index'));

    $post = Post::query()->latest('id')->first();

    expect($post->image)->toStartWith('posts/');
    Storage::disk('public')->assertExists($post->image);
});

it('rejects post image extensions other than jpg and png', function () {
    Storage::fake('public');

    $user = User::factory()->create();

    $response = $this->from(route('posts.create'))->post(route('posts.store'), [
        'title' => 'Bad Image',
        'description' => 'This description is long enough.',
        'creator' => $user->id,
        'image' => uploadedGif('cover.gif'),
    ]);

    $response->assertRedirect(route('posts.create'));
    $response->assertSessionHasErrors('image');
});

it('deletes the old image when updating a post with a new image', function () {
    Storage::fake('public');

    $user = User::factory()->create();
    $post = Post::query()->create([
        'title' => 'Old Image Post',
        'description' => 'This description is long enough.',
        'image' => uploadedPng('old.png'),
        'user_id' => $user->id,
    ]);

    $oldImagePath = $post->image;

    $response = $this->put(route('posts.update', $post->id), [
        'title' => 'Old Image Post',
        'description' => 'This description is still long enough.',
        'creator' => $user->id,
        'image' => uploadedPng('new.png'),
    ]);

    $response->assertRedirect(route('posts.index'));

    $post->refresh();

    expect($post->image)->not->toBe($oldImagePath);
    Storage::disk('public')->assertMissing($oldImagePath);
    Storage::disk('public')->assertExists($post->image);
});

it('deletes the stored image when deleting a post', function () {
    Storage::fake('public');

    $user = User::factory()->create();
    $post = Post::query()->create([
        'title' => 'Delete Image Post',
        'description' => 'This description is long enough.',
        'image' => uploadedPng('delete.png'),
        'user_id' => $user->id,
    ]);

    $imagePath = $post->image;

    $response = $this->delete(route('posts.destroy', $post->id));

    $response->assertRedirect(route('posts.index'));

    Storage::disk('public')->assertMissing($imagePath);
    expect(Post::query()->find($post->id))->toBeNull();
});

function uploadedPng(string $name): UploadedFile
{
    $path = tempnam(sys_get_temp_dir(), 'png');

    file_put_contents($path, base64_decode('iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mP8/x8AAwMCAO+aostAAAAASUVORK5CYII='));

    return new UploadedFile($path, $name, 'image/png', null, true);
}

function uploadedGif(string $name): UploadedFile
{
    $path = tempnam(sys_get_temp_dir(), 'gif');

    file_put_contents($path, base64_decode('R0lGODlhAQABAIAAAAAAAP///ywAAAAAAQABAAACAUwAOw=='));

    return new UploadedFile($path, $name, 'image/gif', null, true);
}
