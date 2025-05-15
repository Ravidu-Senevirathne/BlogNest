<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\Laravel\Facades\Image;

class ImageService
{
    /**
     * Upload and optimize an image
     *
     * @param UploadedFile $image
     * @param string $folder
     * @param string $preset
     * @param string $disk
     * @return array
     */
    public function uploadImage(UploadedFile $image, string $folder = 'images', string $preset = 'thumbnail', string $disk = 'public'): array
    {
        // Generate a unique file name
        $fileName = Str::uuid() . '.' . $image->getClientOriginalExtension();
        $path = $folder . '/' . $fileName;

        // Get preset config
        $presetConfig = config("image.presets.{$preset}");

        if (!$presetConfig) {
            $presetConfig = [
                'width' => 800,
                'height' => null,
                'quality' => 80,
            ];
        }

        // Process the image using Intervention
        $processedImage = Image::read($image)
            ->resize($presetConfig['width'], $presetConfig['height'], function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            })
            ->encodeByExtension($image->getClientOriginalExtension(), $presetConfig['quality'] ?? 80);

        // Store the processed image
        Storage::disk($disk)->put($path, $processedImage->toFilePointer());

        return [
            'path' => $path,
            'url' => Storage::disk($disk)->url($path),
            'file_name' => $fileName,
            'original_name' => $image->getClientOriginalName(),
        ];
    }

    /**
     * Generate responsive images
     *
     * @param UploadedFile $image
     * @param string $folder
     * @param string $disk
     * @return array
     */
    public function uploadResponsiveImage(UploadedFile $image, string $folder = 'images', string $disk = 'public'): array
    {
        $fileName = Str::uuid();
        $extension = $image->getClientOriginalExtension();
        $result = [];

        // Get responsive sizes config
        $sizes = config('image.presets.responsive.sizes', [
            'sm' => ['width' => 400, 'height' => null, 'quality' => 80],
            'md' => ['width' => 800, 'height' => null, 'quality' => 85],
            'lg' => ['width' => 1200, 'height' => null, 'quality' => 90],
        ]);

        foreach ($sizes as $size => $config) {
            $sizeFileName = "{$fileName}-{$size}.{$extension}";
            $path = "{$folder}/{$sizeFileName}";

            // Process the image
            $processedImage = Image::read($image)
                ->resize($config['width'], $config['height'], function ($constraint) {
                    $constraint->aspectRatio();
                    $constraint->upsize();
                })
                ->encodeByExtension($extension, $config['quality'] ?? 80);

            // Store the processed image
            Storage::disk($disk)->put($path, $processedImage->toFilePointer());

            $result[$size] = [
                'path' => $path,
                'url' => Storage::disk($disk)->url($path),
                'width' => $config['width'],
            ];
        }

        $result['original_name'] = $image->getClientOriginalName();
        $result['base_name'] = $fileName;

        return $result;
    }

    /**
     * Delete an image
     *
     * @param string $path
     * @param string $disk
     * @return bool
     */
    public function deleteImage(string $path, string $disk = 'public'): bool
    {
        if (Storage::disk($disk)->exists($path)) {
            return Storage::disk($disk)->delete($path);
        }

        return false;
    }

    /**
     * Delete responsive images
     *
     * @param string $baseName
     * @param string $folder
     * @param string $extension
     * @param string $disk
     * @return bool
     */
    public function deleteResponsiveImages(string $baseName, string $folder = 'images', string $extension = 'jpg', string $disk = 'public'): bool
    {
        $sizes = array_keys(config('image.presets.responsive.sizes', [
            'sm' => [],
            'md' => [],
            'lg' => []
        ]));

        $success = true;

        foreach ($sizes as $size) {
            $path = "{$folder}/{$baseName}-{$size}.{$extension}";

            if (Storage::disk($disk)->exists($path)) {
                $success = Storage::disk($disk)->delete($path) && $success;
            }
        }

        return $success;
    }
}
