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
        'slug',
        'ringkasan',
        'konten',
        'gambar',
        'status',
        'views',
    ];

    protected function casts(): array
    {
        return [
            'views' => 'integer',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (Artikel $artikel) {
            $artikel->slug = Str::slug($artikel->judul);
            $artikel->user_id = $artikel->user_id ?? auth()->id();
        });

        static::updating(function (Artikel $artikel) {
            if ($artikel->isDirty('judul')) {
                $artikel->slug = Str::slug($artikel->judul).($artikel->trashed() ? '-'.$artikel->id : '');
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

    public function scopePublished($query)
    {
        return $query->where('status', 'published');
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }
}
