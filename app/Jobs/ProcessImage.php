<?php

namespace App\Jobs;

use App\Services\ImageService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Http\UploadedFile;

class ProcessImage implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $filePath;
    protected $targetFolder;
    protected $preset;
    protected $disk;
    protected $callback;

    /**
     * Create a new job instance.
     *
     * @param string $filePath
     * @param string $targetFolder
     * @param string $preset
     * @param string $disk
     * @param string|null $callback
     * @return void
     */
    public function __construct(
        string $filePath,
        string $targetFolder = 'images',
        string $preset = 'medium',
        string $disk = 'public',
        ?string $callback = null
    ) {
        $this->filePath = $filePath;
        $this->targetFolder = $targetFolder;
        $this->preset = $preset;
        $this->disk = $disk;
        $this->callback = $callback;
    }

    /**
     * Execute the job.
     *
     * @param ImageService $imageService
     * @return void
     */
    public function handle(ImageService $imageService): void
    {
        try {
            // Create an UploadedFile instance from the stored file
            $file = new UploadedFile(
                storage_path('app/temp/' . $this->filePath),
                basename($this->filePath)
            );

            if ($this->preset === 'responsive') {
                $result = $imageService->uploadResponsiveImage($file, $this->targetFolder, $this->disk);
            } else {
                $result = $imageService->uploadImage($file, $this->targetFolder, $this->preset, $this->disk);
            }

            // Call the callback if provided
            if ($this->callback && class_exists($this->callback) && method_exists($this->callback, 'handle')) {
                app()->call([$this->callback, 'handle'], ['result' => $result]);
            }

            // Clean up the temporary file
            unlink(storage_path('app/temp/' . $this->filePath));
        } catch (\Exception $e) {
            \Log::error('Image processing failed: ' . $e->getMessage(), [
                'file' => $this->filePath,
                'trace' => $e->getTraceAsString()
            ]);

            $this->fail($e);
        }
    }
}
