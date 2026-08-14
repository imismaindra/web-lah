<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\Drivers\Gd\Driver as GdDriver;
use Intervention\Image\ImageManager;

class ImageOptimizer
{
    private ImageManager $manager;

    public function __construct()
    {
        $this->manager = new ImageManager(new GdDriver);
    }

    /**
     * Optimize and store an uploaded image.
     *
     * @return string The stored path
     */
    public function optimizeAndStore(UploadedFile $file, string $directory = 'artikel', int $maxWidth = 1200, int $quality = 80): string
    {
        $image = $this->manager->read($file);

        // Resize if width exceeds maxWidth, maintaining aspect ratio
        if ($image->width() > $maxWidth) {
            $image->scale(width: $maxWidth);
        }

        // Convert to WebP for better compression
        $filename = Str::uuid().'.webp';
        $path = $directory.'/'.$filename;

        $image->toWebp($quality)->save(storage_path('app/public/'.$path));

        return $path;
    }

    /**
     * Optimize and store multiple sizes (responsive images).
     *
     * @return array ['original' => path, 'variants' => [width => path]]
     */
    public function optimizeResponsive(UploadedFile $file, string $directory = 'artikel'): array
    {
        $image = $this->manager->read($file);
        $baseName = Str::uuid();

        $variants = [
            'thumb' => 400,
            'medium' => 800,
            'large' => 1200,
        ];

        $paths = [];

        foreach ($variants as $key => $width) {
            $variantImage = clone $image;
            if ($variantImage->width() > $width) {
                $variantImage->scale(width: $width);
            }
            $filename = $baseName.'-'.$key.'.webp';
            $path = $directory.'/'.$filename;
            $variantImage->toWebp(80)->save(storage_path('app/public/'.$path));
            $paths[$key] = $path;
        }

        // Original optimized (max 1600px)
        if ($image->width() > 1600) {
            $image->scale(width: 1600);
        }
        $originalPath = $directory.'/'.$baseName.'-original.webp';
        $image->toWebp(85)->save(storage_path('app/public/'.$originalPath));
        $paths['original'] = $originalPath;

        return $paths;
    }

    /**
     * Delete an image and its variants.
     */
    public function delete(string $path): void
    {
        Storage::disk('public')->delete($path);

        // Try to delete variants if they exist
        $baseName = pathinfo($path, PATHINFO_FILENAME);
        $dir = dirname($path);
        $variants = ['thumb', 'medium', 'large', 'original'];

        foreach ($variants as $variant) {
            $variantPath = $dir.'/'.$baseName.'-'.$variant.'.webp';
            Storage::disk('public')->delete($variantPath);
        }
    }
}
