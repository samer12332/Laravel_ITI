<?php

namespace App\Models;

use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class Post extends Model
{
    use HasFactory;
    use Sluggable;

    protected $fillable = [
        'title',
        'description',
        'image',
        'user_id',
    ];

    protected static function booted(): void
    {
        static::updating(function (Post $post): void {
            if (!$post->isDirty('image')) {
                return;
            }

            $oldImage = $post->getRawOriginal('image');

            if ($oldImage) {
                Storage::disk('public')->delete($oldImage);
            }
        });

        static::deleting(function (Post $post): void {
            if ($post->getRawOriginal('image')) {
                Storage::disk('public')->delete($post->getRawOriginal('image'));
            }
        });
    }

    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'title',
                'onUpdate' => true,
            ],
        ];
    }

    protected function image(): Attribute
    {
        return Attribute::make(
            set: fn ($value) => $value instanceof UploadedFile
                ? $value->store('posts', 'public')
                : $value,
        );
    }

    protected function imageUrl(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->image
                ? Storage::url($this->image)
                : null,
        );
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function comments(): MorphMany
    {
        return $this->morphMany(Comment::class, 'commentable');
    }
}
