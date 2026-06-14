<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class ImageService
{
    public function uploadImage(UploadedFile $file, string $folder = 'products'): string
    {
        $envPrefix = app()->environment('production') ? 'prod' : 'dev';
        $path = Storage::disk('s3')->putFile("{$envPrefix}/{$folder}", $file);

        return Storage::disk('s3')->url($path);
    }

    public function deleteImage(string $imageUrl): bool
    {
        try {
            $path = $this->extractPathFromUrl($imageUrl);
            if ($path) {
                return Storage::disk('s3')->delete($path);
            }
        } catch (\Exception $e) {
            Log::error('Error deleting image from Supabase: ' . $e->getMessage());
        }

        return false;
    }

    private function extractPathFromUrl(string $url): ?string
    {
        $bucket = config('filesystems.disks.s3.bucket');
        if (preg_match('/\/' . preg_quote($bucket, '/') . '\/(.+)$/', $url, $matches)) {
            return $matches[1];
        }

        return null;
    }
}
