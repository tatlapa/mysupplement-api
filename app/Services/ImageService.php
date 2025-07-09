<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Log;
use Cloudinary\Cloudinary;

class ImageService
{
    /**
     * Upload an image and return the URL
     */
    public function uploadImage(UploadedFile $file, string $folder = 'products'): string
    {
        // Ajouter le préfixe d'environnement au dossier
        $envPrefix = app()->environment('production') ? 'prod' : 'dev';
        $folderWithEnv = $envPrefix . '/' . $folder;
        
        return $this->uploadToCloudinary($file, $folderWithEnv);
    }

    /**
     * Delete an image by URL
     */
    public function deleteImage(string $imageUrl): bool
    {
        return $this->deleteFromCloudinary($imageUrl);
    }

    /**
     * Upload to Cloudinary
     */
    private function uploadToCloudinary(UploadedFile $file, string $folder): string
    {
        $cloudinary = app(Cloudinary::class);
        
        $result = $cloudinary->uploadApi()->upload($file->getRealPath(), [
            'folder' => $folder,
            'public_id' => uniqid(),
        ]);

        return $result['secure_url'];
    }



    /**
     * Delete from Cloudinary
     */
    private function deleteFromCloudinary(string $imageUrl): bool
    {
        try {
            // Extract public_id from Cloudinary URL
            $publicId = $this->extractPublicIdFromUrl($imageUrl);
            if ($publicId) {
                $cloudinary = app(Cloudinary::class);
                $result = $cloudinary->uploadApi()->destroy($publicId);
                return $result['result'] === 'ok';
            }
        } catch (\Exception $e) {
            Log::error('Error deleting image from Cloudinary: ' . $e->getMessage());
        }

        return false;
    }



    /**
     * Extract public_id from Cloudinary URL
     */
    private function extractPublicIdFromUrl(string $url): ?string
    {
        // Cloudinary URL format: https://res.cloudinary.com/cloud_name/image/upload/v1234567890/folder/filename.jpg
        $pattern = '/\/upload\/[^\/]+\/(.+)\.[a-zA-Z]+$/';
        if (preg_match($pattern, $url, $matches)) {
            return $matches[1];
        }

        return null;
    }
} 