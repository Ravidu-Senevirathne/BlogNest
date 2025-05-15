<?php

namespace Tests\Feature\Services;

use App\Services\ImageService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ImageServiceTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_upload_image()
    {
        Storage::fake('public');

        $file = UploadedFile::fake()->image('test-image.jpg', 1000, 1000);

        $imageService = app(ImageService::class);
        $result = $imageService->uploadImage($file);

        $this->assertArrayHasKey('path', $result);
        $this->assertArrayHasKey('url', $result);
        $this->assertArrayHasKey('file_name', $result);
        $this->assertArrayHasKey('original_name', $result);

        Storage::disk('public')->assertExists($result['path']);
    }

    public function test_can_upload_responsive_image()
    {
        Storage::fake('public');

        $file = UploadedFile::fake()->image('test-image.jpg', 1200, 1000);

        $imageService = app(ImageService::class);
        $result = $imageService->uploadResponsiveImage($file);

        $this->assertArrayHasKey('sm', $result);
        $this->assertArrayHasKey('md', $result);
        $this->assertArrayHasKey('lg', $result);

        Storage::disk('public')->assertExists($result['sm']['path']);
        Storage::disk('public')->assertExists($result['md']['path']);
        Storage::disk('public')->assertExists($result['lg']['path']);
    }

    public function test_can_delete_image()
    {
        Storage::fake('public');

        $file = UploadedFile::fake()->image('test-image.jpg');

        $imageService = app(ImageService::class);
        $result = $imageService->uploadImage($file);

        Storage::disk('public')->assertExists($result['path']);

        $deleted = $imageService->deleteImage($result['path']);

        $this->assertTrue($deleted);
        Storage::disk('public')->assertMissing($result['path']);
    }
}
