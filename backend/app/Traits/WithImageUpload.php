<?php

namespace App\Traits;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

trait WithImageUpload
{
    /**
     * Upload an image to storage.
     *
     * @param UploadedFile $image The uploaded file
     * @param string $identifier Identifier for filename generation (e.g., title, name)
     * @param string $path Storage path (e.g., 'covers', 'avatars', 'logos')
     * @return string Relative path to stored image
     */
    protected function uploadImage(UploadedFile $image, string $identifier, string $path = 'covers'): string
    {
        $imageName = time() . '_' . Str::slug($identifier) . '.' . $image->getClientOriginalExtension();

        // Store using explicit 'public' disk to avoid using the 'local' disk
        // which has root set to 'app/private'
        $result = Storage::disk('public')->putFileAs($path, $image, $imageName);

        return "{$path}/" . $imageName;
    }

    /**
     * Update an image, deleting the old one if provided.
     *
     * @param UploadedFile|null $newImage New uploaded image
     * @param string|null $oldImagePath Old image path to delete
     * @param string $identifier Identifier for filename generation
     * @param string $path Storage path (e.g., 'covers', 'avatars', 'logos')
     * @param bool $removeImage Whether to remove the image entirely
     * @return string|null Relative path to stored image, or null if removed
     */
    protected function updateImage(
        ?UploadedFile $newImage,
        ?string $oldImagePath,
        string $identifier,
        string $path = 'covers',
        bool $removeImage = false
    ): ?string {
        // Handle image removal
        if ($removeImage && $oldImagePath) {
            $this->deleteImage($oldImagePath);
            return null;
        }

        // Handle new image upload
        if ($newImage) {
            // Delete old image if exists
            if ($oldImagePath) {
                $this->deleteImage($oldImagePath);
            }
            return $this->uploadImage($newImage, $identifier, $path);
        }

        // Keep existing image
        return $oldImagePath;
    }

    /**
     * Delete an image from storage.
     *
     * @param string|null $imagePath Image path to delete
     * @return void
     */
    protected function deleteImage(?string $imagePath): void
    {
        if ($imagePath) {
            // Use explicit 'public' disk for consistency
            Storage::disk('public')->delete($imagePath);
        }
    }
}
