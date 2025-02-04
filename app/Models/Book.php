<?php

namespace App\Models;

use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Date;

class Book extends Model
{
    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }

    public function scopeTitle(Builder $query, string $title): Builder
    {
        return $query->where('title', 'like', "%{$title}%");
    }

    public function scopePopular(Builder $query): Builder
    {
        return $query->withCount('reviews')->orderByDesc('reviews_count');
    }

    public function scopeHighestRated(Builder $query): Builder
    {
        return $query->withAvg('reviews', 'rating')->orderByDesc('reviews_avg_rating');
    }

    public function scopeBooksWithReviewsOnSpecificDate(Builder $query, Date $from = null, Date $to = null): Builder
    {
        return $query->whereHas('reviews', function (Builder $query) use ($from, $to) {
            $query->whereBetween('reviews.created_at', [$from, $to]);
        });
    }

    public function scopeMinReviews(Builder $query, int $min): Builder
    {
        return $query->whereHas('reviews', function (Builder $query) use ($min) {
            $query->where('reviews_count', '>=', $min);
        });
    }
}
