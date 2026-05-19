<?php

namespace App\Traits;

use App\Models\Comment;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Relations\MorphOne;

trait HasComment
{
    public function comment(): MorphOne
    {
        return $this->morphOne(Comment::class, 'commentable');
    }

    public static function register(array $payload): static
    {
        $text = Arr::pull($payload, 'comment');

        return DB::transaction(fn () => static::executeRegistration($payload, $text));
    }

    public function amend(array $payload): bool
    {
        $text = Arr::pull($payload, 'comment');

        return DB::transaction(fn () => $this->executeAmendment($payload, $text));
    }

    private static function executeRegistration(array $payload, ?string $text): static
    {
        $model = static::create($payload);
        $model->syncComment($text);

        return $model;
    }

    private function executeAmendment(array $payload, ?string $text): bool
    {
        $updated = $this->update($payload);
        $this->syncComment($text);

        return $updated;
    }

    public function syncComment(?string $text): void
    {
        if (empty($text) || containsBadWords($text)) {
            $this->comment()->delete();
            return;
        }

        $this->comment()->updateOrCreate(
            ['livestock_id' => $this->livestock_id],
            ['text' => $text]
        );
    }

}
