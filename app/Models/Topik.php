<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Str;

class Topik extends Model
{
    use HasFactory;

    protected $fillable = ['nama', 'slug', 'deskripsi', 'gambar', 'urutan'];

    protected static function booted(): void
    {
        static::creating(function (Topik $topik) {
            $topik->slug = Str::slug($topik->nama);
        });

        static::updating(function (Topik $topik) {
            if ($topik->isDirty('nama')) {
                $topik->slug = Str::slug($topik->nama);
            }
        });
    }

    public function artikel(): BelongsToMany
    {
        return $this->belongsToMany(Artikel::class);
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }
}
