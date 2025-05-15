<?php

namespace App\View\Components;

use App\Services\MetaService;
use Illuminate\View\Component;

class SeoMeta extends Component
{
    /**
     * The meta service instance.
     *
     * @var MetaService
     */
    protected $metaService;

    /**
     * The meta title.
     *
     * @var string
     */
    public $title;

    /**
     * The meta description.
     *
     * @var string
     */
    public $description;

    /**
     * The meta keywords.
     *
     * @var string|array
     */
    public $keywords;

    /**
     * The social image URL.
     *
     * @var string
     */
    public $image;

    /**
     * The page type.
     *
     * @var string
     */
    public $type;

    /**
     * The canonical URL.
     *
     * @var string
     */
    public $canonical;

    /**
     * The robots directive.
     *
     * @var string
     */
    public $robots;

    /**
     * The author.
     *
     * @var string
     */
    public $author;

    /**
     * Create a new component instance.
     *
     * @param string $title
     * @param string|null $description
     * @param string|array|null $keywords
     * @param string|null $image
     * @param string $type
     * @param string|null $canonical
     * @param string $robots
     * @param string|null $author
     * @return void
     */
    public function __construct(
        string $title,
        ?string $description = null,
        $keywords = null,
        ?string $image = null,
        string $type = 'website',
        ?string $canonical = null,
        string $robots = 'index, follow',
        ?string $author = null
    ) {
        $this->metaService = app('meta');
        $this->title = $title;
        $this->description = $description;
        $this->keywords = $keywords;
        $this->image = $image;
        $this->type = $type;
        $this->canonical = $canonical;
        $this->robots = $robots;
        $this->author = $author;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        $this->metaService
            ->setTitle($this->title)
            ->setType($this->type)
            ->setRobots($this->robots);

        if ($this->description) {
            $this->metaService->setDescription($this->description);
        }

        if ($this->keywords) {
            $this->metaService->setKeywords($this->keywords);
        }

        if ($this->image) {
            $this->metaService->setImage($this->image);
        }

        if ($this->canonical) {
            $this->metaService->setCanonical($this->canonical);
        }

        if ($this->author) {
            $this->metaService->setAuthor($this->author);
        }

        return function (array $data) {
            return $this->metaService->generate();
        };
    }
}
