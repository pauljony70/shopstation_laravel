<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ImageHelper
{
    public static function uploadImage($image)
    {
        if (is_array($image)) {
            $imagePaths = [];

            foreach ($image as $singleImage) {
                $imagePaths[] = self::uploadSingleImage($singleImage);
            }

            return json_encode($imagePaths);
        } else {
            return self::uploadSingleImage($image);
        }
    }

    private static function uploadSingleImage($image)
    {
        $imageName = self::generateEncryptedName($image);

        $datePath = now()->format('Y/m/d');
        $filePath = "uploads/{$datePath}";

        // Store the image in the storage/app/public directory
        $image->storeAs($filePath, $imageName, 'public');

        // Concatenate the file path and image name
        $fullPath = $filePath . '/' . $imageName;

        return $fullPath;
    }

    private static function generateEncryptedName($image)
    {
        $originalName = $image->getClientOriginalName();
        $extension = $image->getClientOriginalExtension();

        // Encrypting the image name to make it unique
        $encryptedName = Str::random(20);

        return "{$encryptedName}.{$extension}";
    }

    public static function deleteImage($imagePath)
    {
        if ($imagePath) {
            // Get the full path of the image
            $fullPath = storage_path("app/public/{$imagePath}");

            // Check if the file exists before attempting to delete it
            if (file_exists($fullPath)) {
                // Delete the image file
                unlink($fullPath);
            }
        }
    }
}
