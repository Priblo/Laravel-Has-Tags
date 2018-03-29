<?php
namespace Priblo\LaravelHasTags\Repositories\Interfaces;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;
use Priblo\LaravelHasTags\Models\Tag;

/**
 * Interface HasTagsRepositoryInterface
 * @package Priblo\LaravelHasTags\Repositories\Interfaces
 */
interface HasTagsRepositoryInterface
{
    /**
     * @param Model $Model
     * @param string $tag_slug
     * @param string|null $type
     * @return Collection
     */
    public function findAllTaggedModelsByModelAndSlugAndType(Model $Model, string $tag_slug, string $type = null) : Collection;

    /**
     * Find one tag by slug and type
     *
     * @param string $tag_slug
     * @param string $type
     * @return null|Tag
     */
    public function findOneTagBySlugAndType(string $tag_slug, string $type = null) : ?Tag;

    /**
     * Create a new Tag
     *
     * @param string $tag_name
     * @param string $type
     * @return Tag
     */
    public function createOneTagFromStringForType(string $tag_name, string $type = null) : Tag;

    /**
     * Updates an individual tag counter
     *
     * @param Tag $Tag
     * @return Tag
     */
    public function updateTagCount(Tag $Tag) : Tag;

    /**
     * @param Model $Model
     * @return Collection
     */
    public function findAllTaggablesByModel(Model $Model) : Collection;

    /**
     * Delete all unused tags
     */
    public function deleteUnusedTags() : void;
}