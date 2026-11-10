<?php

namespace App\Models;

use App\Services\SlugService;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Http\Exceptions\HttpResponseException;

class Penulis extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'nama', 'slug', 'bio', 'avatar', 'website'];

    protected static function booted(): void
    {
        static::creating(function (Penulis $penulis) {
            $penulis->slug = SlugService::generateUnique(
                self::class,
                $penulis->slug,
                $penulis->nama ?? ''
            );
        });

        static::updating(function (Penulis $penulis) {
            if ($penulis->isDirty('slug')) {
                $oldSlug = $penulis->getOriginal('slug');
                $penulis->slug = SlugService::generateUnique(
                    self::class,
                    $penulis->slug,
                    $penulis->nama ?? '',
                    $penulis->id
                );

                if ($oldSlug && $oldSlug !== $penulis->slug) {
                    UrlRedirect::record('penulis/'.$oldSlug, 'penulis/'.$penulis->slug, 301);
                }
            } elseif (empty($penulis->slug) && ! empty($penulis->nama)) {
                $penulis->slug = SlugService::generateUnique(
                    self::class,
                    null,
                    $penulis->nama,
                    $penulis->id
                );
            }
        });
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function artikel(): HasMany
    {
        return $this->hasMany(Artikel::class, 'user_id', 'user_id');
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function resolveRouteBinding($value, $field = null): ?Model
    {
        $penulis = $this->where($field ?? $this->getRouteKeyName(), $value)->first();

        if ($penulis) {
            return $penulis;
        }

        $redirect = UrlRedirect::findRedirect('penulis/'.$value);
        if ($redirect) {
            $redirect->increment('hits');
            $targetUrl = str_starts_with($redirect->target_path, 'http')
                ? $redirect->target_path
                : url($redirect->target_path);

            throw new HttpResponseException(
                redirect()->to($targetUrl, $redirect->status_code ?? 301)
            );
        }

        return null;
    }
}
