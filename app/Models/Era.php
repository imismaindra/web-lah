<?php

namespace App\Models;

use App\Services\SlugService;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Http\Exceptions\HttpResponseException;

class Era extends Model
{
    use HasFactory;

    protected $fillable = ['nama', 'slug', 'periode', 'gambar', 'urutan'];

    protected static function booted(): void
    {
        static::creating(function (Era $era) {
            $era->slug = SlugService::generateUnique(
                self::class,
                $era->slug,
                $era->nama ?? ''
            );
        });

        static::updating(function (Era $era) {
            if ($era->isDirty('slug')) {
                $oldSlug = $era->getOriginal('slug');
                $era->slug = SlugService::generateUnique(
                    self::class,
                    $era->slug,
                    $era->nama ?? '',
                    $era->id
                );

                if ($oldSlug && $oldSlug !== $era->slug) {
                    UrlRedirect::record('era/'.$oldSlug, 'era/'.$era->slug, 301);
                }
            } elseif (empty($era->slug) && ! empty($era->nama)) {
                $era->slug = SlugService::generateUnique(
                    self::class,
                    null,
                    $era->nama,
                    $era->id
                );
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

    public function resolveRouteBinding($value, $field = null): ?Model
    {
        $era = $this->where($field ?? $this->getRouteKeyName(), $value)->first();

        if ($era) {
            return $era;
        }

        $redirect = UrlRedirect::findRedirect('era/'.$value);
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
