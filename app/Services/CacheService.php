<?php

namespace App\Services;

use Closure;
use Illuminate\Support\Facades\Cache;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;

class CacheService
{
    /**
     * Cache duration in seconds (default: 1 hour)
     *
     * @var int
     */
    protected $cacheDuration = 3600;

    /**
     * Set custom cache duration
     *
     * @param int $seconds
     * @return $this
     */
    public function duration(int $seconds)
    {
        $this->cacheDuration = $seconds;
        return $this;
    }

    /**
     * Get data from cache or execute the callback and cache the result
     *
     * @param string $key
     * @param Closure $callback
     * @param int|null $duration
     * @return mixed
     */
    public function remember(string $key, Closure $callback, ?int $duration = null)
    {
        return Cache::remember($key, $duration ?? $this->cacheDuration, $callback);
    }

    /**
     * Cache a model by ID
     *
     * @param string $modelClass
     * @param int $id
     * @param array $relations
     * @param int|null $duration
     * @return Model|null
     */
    public function getModel(string $modelClass, int $id, array $relations = [], ?int $duration = null)
    {
        $key = strtolower(class_basename($modelClass)) . ":{$id}";

        if (!empty($relations)) {
            $key .= ':' . implode(',', $relations);
        }

        return Cache::remember($key, $duration ?? $this->cacheDuration, function () use ($modelClass, $id, $relations) {
            $query = $modelClass::query();

            if (!empty($relations)) {
                $query->with($relations);
            }

            return $query->find($id);
        });
    }

    /**
     * Cache the result of a model query
     *
     * @param string $key
     * @param string $modelClass
     * @param array $conditions
     * @param array $relations
     * @param array $options
     * @param int|null $duration
     * @return Collection
     */
    public function getModels(
        string $key,
        string $modelClass,
        array $conditions = [],
        array $relations = [],
        array $options = [],
        ?int $duration = null
    ) {
        return Cache::remember($key, $duration ?? $this->cacheDuration, function () use ($modelClass, $conditions, $relations, $options) {
            $query = $modelClass::query();

            // Add conditions
            foreach ($conditions as $condition) {
                $query->where(...$condition);
            }

            // Add relations
            if (!empty($relations)) {
                $query->with($relations);
            }

            // Add ordering
            if (isset($options['orderBy'])) {
                $query->orderBy($options['orderBy'], $options['direction'] ?? 'asc');
            }

            // Add limit
            if (isset($options['limit'])) {
                $query->limit($options['limit']);
            }

            // Add pagination
            if (isset($options['paginate'])) {
                return $query->paginate($options['paginate']);
            }

            return $query->get();
        });
    }

    /**
     * Clear cache by key or pattern
     *
     * @param string $key
     * @return bool
     */
    public function clear(string $key): bool
    {
        return Cache::forget($key);
    }

    /**
     * Clear cache for a model
     *
     * @param string $modelClass
     * @param int|null $id
     * @return void
     */
    public function clearModel(string $modelClass, ?int $id = null): void
    {
        $key = strtolower(class_basename($modelClass));

        if ($id !== null) {
            Cache::forget("{$key}:{$id}");
        } else {
            $this->clearByPattern("{$key}:*");
        }
    }

    /**
     * Clear cache by pattern using cache tags (if available)
     *
     * @param string $pattern
     * @return void
     */
    public function clearByPattern(string $pattern): void
    {
        // This is a simplified implementation. For a production application,
        // you might need to use a different approach depending on your cache driver
        // since some drivers (like file) don't support wildcard deletion
        if (Cache::getStore() instanceof \Illuminate\Cache\TaggableStore) {
            $tag = explode(':*', $pattern)[0];
            Cache::tags([$tag])->flush();
        } else {
            // Fallback for non-taggable cache stores
            // This is limited and won't work for all cache drivers
            Cache::flush();
        }
    }
}
