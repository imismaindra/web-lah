<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Pageview extends Model
{
    use HasFactory;

    public const UPDATED_AT = null;

    protected $fillable = [
        'artikel_id',
        'user_id',
        'ip_address',
        'user_agent',
        'referer',
    ];

    public function artikel(): BelongsTo
    {
        return $this->belongsTo(Artikel::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
