<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ImageCompressionService
{
    /**
     * Compress an uploaded image and save it to storage.
     *
     * @param UploadedFile $file The uploaded file.
     * @param string $directory The target directory within the storage disk.
     * @param string $disk The storage disk to use.
     * @param int $quality Compression quality (1-100).
     * @param int|null $maxWidth Optional maximum width to resize the image to.
     * @return string|false The stored path (e.g. 'payments/filename.jpg') or false on failure.
     */
    public static function compressAndStore(UploadedFile $file, string $directory, string $disk = 'public', int $quality = 75, ?int $maxWidth = 1200)
    {
        // Check if GD extension is loaded
        if (!extension_loaded('gd')) {
            return $file->store($directory, $disk);
        }

        $mime = $file->getClientMimeType();
        $realPath = $file->getRealPath();
        $extension = strtolower($file->getClientOriginalExtension());

        // Create image resource based on mime type
        switch ($mime) {
            case 'image/jpeg':
            case 'image/jpg':
                $image = @imagecreatefromjpeg($realPath);
                break;
            case 'image/png':
                $image = @imagecreatefrompng($realPath);
                break;
            case 'image/webp':
                $image = @imagecreatefromwebp($realPath);
                break;
            case 'image/gif':
                $image = @imagecreatefromgif($realPath);
                break;
            default:
                // Fallback for svg/others
                return $file->store($directory, $disk);
        }

        if (!$image) {
            return $file->store($directory, $disk);
        }

        // Optional resizing
        if ($maxWidth !== null) {
            $origWidth = imagesx($image);
            $origHeight = imagesy($image);

            if ($origWidth > $maxWidth) {
                $newWidth = $maxWidth;
                $newHeight = (int) (($origHeight / $origWidth) * $newWidth);

                $resizedImage = imagecreatetruecolor($newWidth, $newHeight);

                // Preserve transparency
                if ($mime === 'image/png' || $mime === 'image/webp') {
                    imagealphablending($resizedImage, false);
                    imagesavealpha($resizedImage, true);
                }

                imagecopyresampled($resizedImage, $image, 0, 0, 0, 0, $newWidth, $newHeight, $origWidth, $origHeight);
                imagedestroy($image);
                $image = $resizedImage;
            }
        }

        // Create a temporary file path
        $tempPath = tempnam(sys_get_temp_dir(), 'img_comp_');

        $saved = false;
        if ($extension === 'png') {
            // PNG quality parameter is 0 (no compression) to 9 (max compression).
            // Convert 0-100 quality range to 0-9 png scale.
            // 75% quality => roughly compression level 6.
            $pngQuality = (int) round((100 - $quality) / 10);
            if ($pngQuality < 0) $pngQuality = 0;
            if ($pngQuality > 9) $pngQuality = 9;
            $saved = @imagepng($image, $tempPath, $pngQuality);
        } elseif ($extension === 'webp') {
            $saved = @imagewebp($image, $tempPath, $quality);
        } else {
            // default to jpeg
            $saved = @imagejpeg($image, $tempPath, $quality);
        }

        if (!$saved) {
            imagedestroy($image);
            if (file_exists($tempPath)) {
                unlink($tempPath);
            }
            return $file->store($directory, $disk);
        }

        // Store the file using Laravel storage
        $filename = Str::random(40) . '.' . $extension;
        $targetPath = $directory . '/' . $filename;

        Storage::disk($disk)->put($targetPath, fopen($tempPath, 'r'));

        // Clean up
        imagedestroy($image);
        if (file_exists($tempPath)) {
            unlink($tempPath);
        }

        return $targetPath;
    }
}
