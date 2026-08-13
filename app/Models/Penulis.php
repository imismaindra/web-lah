<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Penulis extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'nama', 'slug', 'bio', 'avatar', 'website'];

    protected static function booted(): void
    {
        static::creating(function (Penulis $penulis) {
            $penulis->slug = Str::slug($penulis->nama);
        });

        static::updating(function (Penulis $penulis) {
            if ($penulis->isDirty('nama')) {
                $penulis->slug = Str::slug($penulis->nama);
            }
        });
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function artikel(): HasMany
    {
        return $this->hasMany(Artikel::class);
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }
}
