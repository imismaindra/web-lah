<?php

namespace App\Models;

use App\Services\SlugService;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Http\Exceptions\HttpResponseException;

class Kategori extends Model
{
    use HasFactory;

    protected $fillable = ['nama', 'slug', 'deskripsi'];

    protected static function booted(): void
    {
        static::creating(function (Kategori $kategori) {
            $kategori->slug = SlugService::generateUnique(
                self::class,
                $kategori->slug,
                $kategori->nama ?? ''
            );
        });

        static::updating(function (Kategori $kategori) {
            if ($kategori->isDirty('slug')) {
                $oldSlug = $kategori->getOriginal('slug');
                $kategori->slug = SlugService::generateUnique(
                    self::class,
                    $kategori->slug,
                    $kategori->nama ?? '',
                    $kategori->id
                );

                if ($oldSlug && $oldSlug !== $kategori->slug) {
                    UrlRedirect::record('kategori/'.$oldSlug, 'kategori/'.$kategori->slug, 301);
                }
            } elseif (empty($kategori->slug) && ! empty($kategori->nama)) {
                $kategori->slug = SlugService::generateUnique(
                    self::class,
                    null,
                    $kategori->nama,
                    $kategori->id
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
        $kategori = $this->where($field ?? $this->getRouteKeyName(), $value)->first();

        if ($kategori) {
            return $kategori;
        }

        $redirect = UrlRedirect::findRedirect('kategori/'.$value);
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
