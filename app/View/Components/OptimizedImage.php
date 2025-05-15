<?php

namespace App\View\Components;

use Illuminate\View\Component;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Laravel\Facades\Image;

class OptimizedImage extends Component
{
    /**
     * The image source.
     *
     * @var string
     */
    public $src;

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
     * Image width.
     *
     * @var int|null
     */
    public $width;

    /**
     * Image height.
     *
     * @var int|null
     */
    public $height;

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
     * Create a new component instance.
     *
     * @param string $src
     * @param string $alt
     * @param string|null $class
     * @param int|null $width
     * @param int|null $height
     * @param bool $lazy
     * @param string|null $loading
     * @return void
     */
    public function __construct(
        string $src,
        string $alt,
        ?string $class = null,
        ?int $width = null,
        ?int $height = null,
        bool $lazy = true,
        ?string $loading = null
    ) {
        $this->src = $src;
        $this->alt = $alt;
        $this->class = $class;
        $this->width = $width;
        $this->height = $height;
        $this->lazy = $lazy;
        $this->loading = $loading ?: ($lazy ? 'lazy' : 'eager');
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.optimized-image');
    }
}
