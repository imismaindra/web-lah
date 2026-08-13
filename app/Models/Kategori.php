<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Kategori extends Model
{
    use HasFactory;

    protected $fillable = ['nama', 'slug', 'deskripsi'];

    protected static function booted(): void
    {
        static::creating(function (Kategori $kategori) {
            $kategori->slug = Str::slug($kategori->nama);
        });

        static::updating(function (Kategori $kategori) {
            if ($kategori->isDirty('nama')) {
                $kategori->slug = Str::slug($kategori->nama);
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
