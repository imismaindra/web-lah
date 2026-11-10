<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UrlRedirect extends Model
{
    use HasFactory;

    protected $fillable = [
        'source_path',
        'target_path',
        'status_code',
        'hits',
    ];

    protected function casts(): array
    {
        return [
            'status_code' => 'integer',
            'hits' => 'integer',
        ];
    }

    public static function normalizePath(string $path): string
    {
        $parsed = parse_url($path, PHP_URL_PATH);
        $clean = $parsed !== null ? $parsed : $path;

        return trim($clean, '/');
    }

    public static function record(string $sourcePath, string $targetPath, int $statusCode = 301): ?self
    {
        $source = self::normalizePath($sourcePath);
        $target = self::normalizePath($targetPath);

        if ($source === '' || $target === '' || $source === $target) {
            return null;
        }

        // Prevent redirect chains by pointing previous hops to the new target
        static::where('target_path', $source)->update(['target_path' => $target]);

        return static::updateOrCreate(
            ['source_path' => $source],
            [
                'target_path' => $target,
                'status_code' => $statusCode,
            ]
        );
    }

    public static function findRedirect(string $path): ?self
    {
        $normalized = self::normalizePath($path);
        if ($normalized === '') {
            return null;
        }

        return static::where('source_path', $normalized)->first();
    }
}
