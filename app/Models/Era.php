<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Era extends Model
{
    use HasFactory;

    protected $fillable = ['nama', 'slug', 'periode', 'gambar', 'urutan'];

    protected static function booted(): void
    {
        static::creating(function (Era $era) {
            $era->slug = Str::slug($era->nama);
        });

        static::updating(function (Era $era) {
            if ($era->isDirty('nama')) {
                $era->slug = Str::slug($era->nama);
            }
        });
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
