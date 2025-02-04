<?php

namespace App\Models;

use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;

class Book extends Model
{
    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }

    public function scopeFilter(Builder $query, array $filters): Builder
    {
        return $query
            ->when(isset($filters['title']), fn($q) => $q->where('title', 'like', "%{$filters['title']}%"))
            ->when(isset($filters['popular']), fn($q) => $q->withCount('reviews')->orderByDesc('reviews_count'))
            ->when(isset($filters['highest_rated']), fn($q) => $q->withAvg('reviews', 'rating')->orderByDesc('reviews_avg_rating'))
            ->when(isset($filters['min_reviews']), fn($q) => $q->whereHas('reviews', fn($q) => $q->havingRaw('COUNT(*) >= ?', [$filters['min_reviews']])))
            ->when(
                isset($filters['from']) && isset($filters['to']),
                fn($q) =>
                $q->whereHas('reviews', fn($q) => $q->whereBetween('reviews.created_at', [
                    Carbon::parse($filters['from']),
                    Carbon::parse($filters['to'])
                ]))
            );
    }
}
