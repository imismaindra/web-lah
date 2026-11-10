<?php

namespace App\Services;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class SlugService
{
    /**
     * Generate a collision-free slug for an Eloquent model.
     *
     * @param  class-string<Model>  $modelClass
     */
    public static function generateUnique(
        string $modelClass,
        ?string $customSlug,
        string $fallbackTitle,
        ?int $ignoreId = null
    ): string {
        $raw = filled($customSlug) ? (string) $customSlug : $fallbackTitle;
        $baseSlug = Str::slug($raw);

        if ($baseSlug === '') {
            $baseSlug = 'item';
        }

        $slug = $baseSlug;
        $counter = 1;

        $hasSoftDeletes = in_array(SoftDeletes::class, class_uses_recursive($modelClass), true);

        while (true) {
            $query = $modelClass::query();

            if ($hasSoftDeletes) {
                $query->withTrashed();
            }

            if ($ignoreId !== null) {
                $query->where('id', '!=', $ignoreId);
            }

            $exists = $query->where('slug', $slug)->exists();

            if (! $exists) {
                return $slug;
            }

            $slug = "{$baseSlug}-{$counter}";
            $counter++;
        }
    }
}
