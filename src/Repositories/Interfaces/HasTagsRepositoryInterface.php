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
    public function findAllTaggedModelsByModelAndSlugAndType(Model $Model, string $tag_slug, string $type = null) : Collection;

    public function findOneTagBySlugAndType(string $tag_slug, $type = null) : ?Tag;

    public function createOneTagFromStringForType(string $tag_name, $type = null) : Tag;

    public function updateTagCount(Tag $Tag) : Tag;

    public function findAllTaggablesByModel(Model $Model) : Collection;

    public function deleteUnusedTags() : void;
}