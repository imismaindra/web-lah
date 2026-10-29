<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Artikel extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'kategori_id',
        'era_id',
        'user_id',
        'judul',
        'meta_title',
        'meta_description',
        'slug',
        'ringkasan',
        'konten',
        'reading_time',
        'faq',
        'gambar',
        'status',
        'published_at',
        'views',
    ];

    protected function casts(): array
    {
        return [
            'views' => 'integer',
            'reading_time' => 'integer',
            'faq' => 'array',
            'published_at' => 'datetime',
        ];
    }

    public static function calculateReadingTime(?string $konten): int
    {
        $cleanText = trim(strip_tags($konten ?? ''));
        if ($cleanText === '') {
            return 1;
        }

        $wordCount = str_word_count($cleanText);

        return (int) max(1, ceil($wordCount / 200));
    }

    protected static function booted(): void
    {
        static::creating(function (Artikel $artikel) {
            $artikel->slug = Str::slug($artikel->judul);
            $artikel->user_id = $artikel->user_id ?? auth()->id();

            if ($artikel->status === 'published' && is_null($artikel->published_at)) {
                $artikel->published_at = now();
            }

            if (is_null($artikel->reading_time)) {
                $artikel->reading_time = self::calculateReadingTime($artikel->konten);
            }
        });

        static::updating(function (Artikel $artikel) {
            if ($artikel->isDirty('judul')) {
                $artikel->slug = Str::slug($artikel->judul).($artikel->trashed() ? '-'.$artikel->id : '');
            }

            if ($artikel->isDirty('konten') || is_null($artikel->reading_time)) {
                $artikel->reading_time = self::calculateReadingTime($artikel->konten);
            }

            if ($artikel->status === 'published' && is_null($artikel->published_at)) {
                $artikel->published_at = now();
            }
        });
    }

    public function kategori(): BelongsTo
    {
        return $this->belongsTo(Kategori::class);
    }

    public function era(): BelongsTo
    {
        return $this->belongsTo(Era::class);
    }

    public function topiks(): BelongsToMany
    {
        return $this->belongsToMany(Topik::class);
    }

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function komentars(): HasMany
    {
        return $this->hasMany(Komentar::class);
    }

    public function reaksis(): HasMany
    {
        return $this->hasMany(Reaksi::class);
    }

    public function bookmarks(): HasMany
    {
        return $this->hasMany(Bookmark::class);
    }

    public function pageviews(): HasMany
    {
        return $this->hasMany(Pageview::class);
    }

    public function isPublished(): bool
    {
        if ($this->status === 'published') {
            return is_null($this->published_at) || $this->published_at->lte(now());
        }

        if ($this->status === 'scheduled') {
            return ! is_null($this->published_at) && $this->published_at->lte(now());
        }

        return false;
    }

    public function isScheduled(): bool
    {
        return $this->status === 'scheduled' && ! is_null($this->published_at) && $this->published_at->gt(now());
    }

    public function scopePublished($query)
    {
        return $query->where(function ($q) {
            $q->where(function ($published) {
                $published->where('status', 'published')
                    ->where(function ($sub) {
                        $sub->whereNull('published_at')
                            ->orWhere('published_at', '<=', now());
                    });
            })->orWhere(function ($scheduled) {
                $scheduled->where('status', 'scheduled')
                    ->whereNotNull('published_at')
                    ->where('published_at', '<=', now());
            });
        });
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }
}
