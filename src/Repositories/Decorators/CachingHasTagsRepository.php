<?php
namespace Priblo\LaravelHasTags\Repositories\Decorators;

use Illuminate\Contracts\Cache\Repository as Cache;
use Illuminate\Database\Eloquent\Model;
use Priblo\LaravelHasTags\Models\Tag;
use Priblo\LaravelHasTags\Models\Taggable;
use Priblo\LaravelHasTags\Repositories\EloquentHasTagsRepository;
use Priblo\LaravelHasTags\Repositories\Interfaces\HasTagsRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

/**
 * Class CachingHasTagsRepository
 * @package Priblo\LaravelHasTags\Repositories\Decorators
 */
class CachingHasTagsRepository implements HasTagsRepositoryInterface
{

    const CACHE_PREFIX = 'LHT';

    /**
     * @var EloquentHasTagsRepository
     */
    protected $repository;

    /**
     * @var Cache
     */
    protected $cache;

    /**
     * @var integer
     */
    protected $cache_expiry;

    /**
     * CachingHasTagsRepository constructor.
     * @param EloquentHasTagsRepository $repository
     * @param Cache $cache
     */
    public function __construct(EloquentHasTagsRepository $repository, Cache $cache)
    {
        $this->repository = $repository;
        $this->cache = $cache;
        $this->cache_expiry = config('has-tags.cache_expiry');
    }

    public function findAllTaggedModelsByModelAndSlugAndType(Model $Model, string $tag_slug, string $type = null) : Collection
    {
        return $this->repository->findAllTaggedModelsByModelAndSlugAndType($Model, $tag_slug, $type);
    }

    public function findOneTagBySlugAndType(string $tag_slug, string $type = null) : ?Tag
    {
        return $this->cache->tags($this->cacheTagForTag())->remember($this->cacheKeyForTag($tag_slug, $type), $this->cache_expiry, function () use ($tag_slug, $type) {
            return $this->repository->findOneTagBySlugAndType($tag_slug, $type);
        });
    }

    public function createOneTagFromStringForType(string $tag_name, string $type = null) : Tag
    {
        $Tag = $this->repository->createOneTagFromStringForType($tag_name, $type);
        $this->cache->tags($this->cacheTagForTag())->put($this->cacheKeyForTag($Tag->slug,$Tag->type), $Tag, $this->cache_expiry);
        return $Tag;
    }

    public function updateTagCount(Tag $Tag) : Tag
    {
        return $this->repository->updateTagCount($Tag);
    }

    public function findAllTaggablesByModel(Model $Model) : Collection
    {
        return $this->repository->findAllTaggablesByModel($Model);
    }

    public function deleteUnusedTags() : void
    {
        $this->repository->deleteUnusedTags();
        $this->cache->tags($this->cacheTagForTag())->flush();
    }

    public function deleteTaggable(Taggable $Taggable) : void
    {
        $Taggable->delete();
        $this->cache->tags($this->cacheTagForTag())->flush();
    }

    /**
     * Generate the cache key string for a Tag model
     *
     * @param string $tag_slug
     * @param string|null $type
     * @return string
     */
    private function cacheKeyForTag(string $tag_slug, string $type = null) : string
    {
        return self::CACHE_PREFIX.'_Tag-'.$tag_slug.'-'.(string)$type;
    }

    /**
     * @return string
     */
    private function cacheTagForTag() : string
    {
        return self::CACHE_PREFIX.'_Tag';
    }
}