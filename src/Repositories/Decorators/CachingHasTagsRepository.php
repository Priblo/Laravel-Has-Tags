<?php
namespace Priblo\LaravelHasTags\Repositories\Decorators;

use Illuminate\Contracts\Cache\Repository as Cache;
use Illuminate\Database\Eloquent\Model;
use Priblo\LaravelHasTags\Models\Tag;
use Priblo\LaravelHasTags\Repositories\EloquentHasTagsRepository;
use Priblo\LaravelHasTags\Repositories\Interfaces\HasTagsRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

/**
 * Class CachingHasTagsRepository
 * @package Priblo\LaravelHasTags\Repositories\Decorators
 */
class CachingHasTagsRepository implements HasTagsRepositoryInterface
{
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
        return $this->repository->findOneTagBySlugAndType($tag_slug, $type);
    }

    public function createOneTagFromStringForType(string $tag_name, string $type = null) : Tag
    {
        return $this->repository->createOneTagFromStringForType($tag_name, $type);
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
    }
}