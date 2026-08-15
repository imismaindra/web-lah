<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Komentar extends Model
{
    use HasFactory;

    protected $fillable = ['artikel_id', 'user_id', 'parent_id', 'nama', 'isi', 'status'];

    public function artikel(): BelongsTo
    {
        return $this->belongsTo(Artikel::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(Komentar::class, 'parent_id');
    }

    public function replies(): HasMany
    {
        return $this->hasMany(Komentar::class, 'parent_id');
    }

    public function scopePublished($query)
    {
        return $query->where('status', 'published');
    }

    public function displayName(): string
    {
        return $this->user?->name ?? $this->nama ?? 'Tamu';
    }
}
