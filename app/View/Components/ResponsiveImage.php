<?php

namespace App\View\Components;

use Illuminate\View\Component;

class ResponsiveImage extends Component
{
    /**
     * The image base name.
     *
     * @var string
     */
    public $baseName;

    /**
     * The image extension.
     *
     * @var string
     */
    public $extension;

    /**
     * The folder path.
     *
     * @var string
     */
    public $folder;

    /**
     * The image alt text.
     *
     * @var string
     */
    public $alt;

    /**
     * CSS classes.
     *
     * @var string
     */
    public $class;

    /**
     * Lazy loading.
     *
     * @var bool
     */
    public $lazy;

    /**
     * Image loading strategy.
     *
     * @var string
     */
    public $loading;

    /**
     * The storage disk.
     *
     * @var string
     */
    public $disk;

    /**
     * Create a new component instance.
     *
     * @param string $baseName
     * @param string $alt
     * @param string $extension
     * @param string $folder
     * @param string|null $class
     * @param bool $lazy
     * @param string|null $loading
     * @param string $disk
     * @return void
     */
    public function __construct(
        string $baseName,
        string $alt,
        string $extension = 'jpg',
        string $folder = 'images',
        ?string $class = null,
        bool $lazy = true,
        ?string $loading = null,
        string $disk = 'public'
    ) {
        $this->baseName = $baseName;
        $this->extension = $extension;
        $this->folder = $folder;
        $this->alt = $alt;
        $this->class = $class;
        $this->lazy = $lazy;
        $this->loading = $loading ?: ($lazy ? 'lazy' : 'eager');
        $this->disk = $disk;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        // Get responsive sizes from config
        $sizes = config('image.presets.responsive.sizes', [
            'sm' => ['width' => 400],
            'md' => ['width' => 800],
            'lg' => ['width' => 1200],
        ]);

        // Generate srcset and URLs
        $srcset = [];
        $urls = [];

        foreach ($sizes as $size => $config) {
            $url = asset("storage/{$this->folder}/{$this->baseName}-{$size}.{$this->extension}");
            $srcset[] = "{$url} {$config['width']}w";
            $urls[$size] = $url;
        }

        return view('components.responsive-image', [
            'srcset' => implode(', ', $srcset),
            'sizes' => '(max-width: 600px) 400px, (max-width: 1200px) 800px, 1200px',
            'urls' => $urls,
            'defaultUrl' => $urls['md'] ?? array_values($urls)[0],
        ]);
    }
}
