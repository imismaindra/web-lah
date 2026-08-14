<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\ImageOptimizer;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UploadController extends Controller
{
    public function __construct(
        private ImageOptimizer $imageOptimizer
    ) {}

    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'file' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:4096',
        ]);

        $path = $this->imageOptimizer->optimizeAndStore($request->file('file'), 'artikel/konten');

        return response()->json([
            'url' => asset('storage/'.$path),
            'path' => $path,
        ]);
    }
}
