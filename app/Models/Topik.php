<?php

namespace App\Models;

use App\Services\SlugService;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Http\Exceptions\HttpResponseException;

class Topik extends Model
{
    use HasFactory;

    protected $fillable = ['nama', 'slug', 'deskripsi', 'gambar', 'urutan'];

    protected static function booted(): void
    {
        static::creating(function (Topik $topik) {
            $topik->slug = SlugService::generateUnique(
                self::class,
                $topik->slug,
                $topik->nama ?? ''
            );
        });

        static::updating(function (Topik $topik) {
            if ($topik->isDirty('slug')) {
                $oldSlug = $topik->getOriginal('slug');
                $topik->slug = SlugService::generateUnique(
                    self::class,
                    $topik->slug,
                    $topik->nama ?? '',
                    $topik->id
                );

                if ($oldSlug && $oldSlug !== $topik->slug) {
                    UrlRedirect::record('topik/'.$oldSlug, 'topik/'.$topik->slug, 301);
                }
            } elseif (empty($topik->slug) && ! empty($topik->nama)) {
                $topik->slug = SlugService::generateUnique(
                    self::class,
                    null,
                    $topik->nama,
                    $topik->id
                );
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

    public function resolveRouteBinding($value, $field = null): ?Model
    {
        $topik = $this->where($field ?? $this->getRouteKeyName(), $value)->first();

        if ($topik) {
            return $topik;
        }

        $redirect = UrlRedirect::findRedirect('topik/'.$value);
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
