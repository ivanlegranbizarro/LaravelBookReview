<?php

namespace App\Models;

use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Book extends Model
{
    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }

    public function getReviewsAvgRatingAttribute(): float
    {
        return round($this->reviews->avg('rating'), 2);
    }

    public function getReviewsCountAttribute(): int
    {
        return $this->reviews->count();
    }

    public function scopeFilter(Builder $query, array $filters): Builder
    {
        return $query
            ->when(
                $filters['filter'] ?? null === '',
                fn($q) => $q->orderByDesc('created_at')
            )
            ->when(
                $filters['filter'] ?? null === 'popular',
                fn($q) => $q->withCount('reviews')->orderByDesc('reviews_count')
            );
    }
}
