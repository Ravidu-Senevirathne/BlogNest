<?php

namespace App\Services;

use Illuminate\Support\Facades\URL;

class MetaService
{
    protected $title;
    protected $description;
    protected $keywords;
    protected $image;
    protected $url;
    protected $type;
    protected $author;
    protected $robots;
    protected $canonical;
    protected $siteName;

    public function __construct()
    {
        $this->siteName = config('app.name', 'BlogNest');
        $this->type = 'website';
        $this->robots = 'index, follow';
        $this->url = URL::current();
        $this->author = null;
        $this->canonical = URL::current();
    }

    /**
     * Set the meta title
     *
     * @param string $title
     * @return $this
     */
    public function setTitle(string $title)
    {
        $this->title = $title;
        return $this;
    }

    /**
     * Set the meta description
     *
     * @param string $description
     * @return $this
     */
    public function setDescription(string $description)
    {
        $this->description = $description;
        return $this;
    }

    /**
     * Set the meta keywords
     *
     * @param string|array $keywords
     * @return $this
     */
    public function setKeywords($keywords)
    {
        if (is_array($keywords)) {
            $keywords = implode(', ', $keywords);
        }

        $this->keywords = $keywords;
        return $this;
    }

    /**
     * Set the social sharing image
     *
     * @param string $imageUrl
     * @return $this
     */
    public function setImage(string $imageUrl)
    {
        $this->image = $imageUrl;
        return $this;
    }

    /**
     * Set the page URL
     *
     * @param string $url
     * @return $this
     */
    public function setUrl(string $url)
    {
        $this->url = $url;
        $this->canonical = $url;
        return $this;
    }

    /**
     * Set the content type
     *
     * @param string $type
     * @return $this
     */
    public function setType(string $type)
    {
        $this->type = $type;
        return $this;
    }

    /**
     * Set the author
     *
     * @param string $author
     * @return $this
     */
    public function setAuthor(string $author)
    {
        $this->author = $author;
        return $this;
    }

    /**
     * Set robots directive
     *
     * @param string $robots
     * @return $this
     */
    public function setRobots(string $robots)
    {
        $this->robots = $robots;
        return $this;
    }

    /**
     * Set canonical URL
     *
     * @param string $canonical
     * @return $this
     */
    public function setCanonical(string $canonical)
    {
        $this->canonical = $canonical;
        return $this;
    }

    /**
     * Set article meta for blog posts
     *
     * @param string $author
     * @param string $publishedTime
     * @param string $modifiedTime
     * @param array $tags
     * @param string|null $section
     * @return $this
     */
    public function setArticleMeta(string $author, string $publishedTime, string $modifiedTime = null, array $tags = [], ?string $section = null)
    {
        $this->setType('article');
        $this->setAuthor($author);

        $this->articleMeta = [
            'published_time' => $publishedTime,
            'modified_time' => $modifiedTime ?? $publishedTime,
            'tags' => $tags,
            'section' => $section,
        ];

        return $this;
    }

    /**
     * Generate meta tags HTML
     *
     * @return string
     */
    public function generate(): string
    {
        $html = '';

        // Basic meta tags
        if ($this->title) {
            $html .= '<title>' . e($this->title) . ' | ' . e($this->siteName) . '</title>' . PHP_EOL;
            $html .= '<meta name="title" content="' . e($this->title) . '">' . PHP_EOL;
        }

        if ($this->description) {
            $html .= '<meta name="description" content="' . e($this->description) . '">' . PHP_EOL;
        }

        if ($this->keywords) {
            $html .= '<meta name="keywords" content="' . e($this->keywords) . '">' . PHP_EOL;
        }

        if ($this->robots) {
            $html .= '<meta name="robots" content="' . e($this->robots) . '">' . PHP_EOL;
        }

        if ($this->author) {
            $html .= '<meta name="author" content="' . e($this->author) . '">' . PHP_EOL;
        }

        // Canonical URL
        $html .= '<link rel="canonical" href="' . e($this->canonical) . '">' . PHP_EOL;

        // Open Graph meta tags for social sharing
        $html .= '<meta property="og:title" content="' . e($this->title) . '">' . PHP_EOL;
        $html .= '<meta property="og:site_name" content="' . e($this->siteName) . '">' . PHP_EOL;
        $html .= '<meta property="og:type" content="' . e($this->type) . '">' . PHP_EOL;
        $html .= '<meta property="og:url" content="' . e($this->url) . '">' . PHP_EOL;
        $html .= '<meta property="og:locale" content="' . e(app()->getLocale()) . '">' . PHP_EOL;

        if ($this->description) {
            $html .= '<meta property="og:description" content="' . e($this->description) . '">' . PHP_EOL;
        }

        if ($this->image) {
            $html .= '<meta property="og:image" content="' . e($this->image) . '">' . PHP_EOL;
            $html .= '<meta property="og:image:alt" content="' . e($this->title) . '">' . PHP_EOL;
        }

        // Twitter card meta tags
        $html .= '<meta name="twitter:card" content="summary_large_image">' . PHP_EOL;
        $html .= '<meta name="twitter:title" content="' . e($this->title) . '">' . PHP_EOL;

        if ($this->description) {
            $html .= '<meta name="twitter:description" content="' . e($this->description) . '">' . PHP_EOL;
        }

        if ($this->image) {
            $html .= '<meta name="twitter:image" content="' . e($this->image) . '">' . PHP_EOL;
        }

        // Article specific meta tags
        if ($this->type === 'article' && isset($this->articleMeta)) {
            if ($this->author) {
                $html .= '<meta property="article:author" content="' . e($this->author) . '">' . PHP_EOL;
            }

            $html .= '<meta property="article:published_time" content="' . e($this->articleMeta['published_time']) . '">' . PHP_EOL;
            $html .= '<meta property="article:modified_time" content="' . e($this->articleMeta['modified_time']) . '">' . PHP_EOL;

            if (!empty($this->articleMeta['section'])) {
                $html .= '<meta property="article:section" content="' . e($this->articleMeta['section']) . '">' . PHP_EOL;
            }

            if (isset($this->articleMeta['tags']) && !empty($this->articleMeta['tags'])) {
                foreach ($this->articleMeta['tags'] as $tag) {
                    $html .= '<meta property="article:tag" content="' . e($tag) . '">' . PHP_EOL;
                }
            }
        }

        return $html;
    }
}
