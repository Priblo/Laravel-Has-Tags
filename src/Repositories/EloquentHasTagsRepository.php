<?php
namespace Priblo\LaravelHasTags\Repositories;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Priblo\LaravelHasTags\Models\Tag;
use Priblo\LaravelHasTags\Models\Taggable;
use Priblo\LaravelHasTags\Repositories\Interfaces\HasTagsRepositoryInterface;

/**
 * Class EloquentHasSettingRepository
 * @package Priblo\LaravelHasSettings\Repositories
 */
class EloquentHasTagsRepository implements HasTagsRepositoryInterface {

    private $Tag;
    private $Taggable;

    /**
     * EloquentHasTagsRepository constructor.
     * @param Tag $Tag
     * @param Taggable $Taggable
     */
    public function __construct(Tag $Tag, Taggable $Taggable)
    {
        $this->Tag = $Tag;
        $this->Taggable = $Taggable;
    }

    /**
     * Find one tag by slug and type
     *
     * @param string $tag_slug
     * @param string $type
     * @return null|Tag
     */
    public function findOneTagBySlugAndType(string $tag_slug, $type = null)
    {
        return $this->Tag
            ->where(['slug'=> $tag_slug, 'type' => $type])
            ->first()
            ;
    }

    /**
     * Create a new Tag
     *
     * @param string $tag_name
     * @param string $type
     * @return Tag
     */
    public function createOneTagFromStringForType(string $tag_name, $type = null) : Tag
    {
        $Tag = new Tag();
        $Tag->type = $type;
        $Tag->name = $tag_name;
        $Tag->slug = str_slug($tag_name);
        $Tag->count = 1;
        $Tag->save();

        return $Tag;
    }

    /**
     * Updates an individual tag counter
     *
     * @param Tag $Tag
     * @return Tag
     */
    public function updateTagCount(Tag $Tag) : Tag
    {
        $count = $this->Taggable
            ->where('tag_id', $Tag->getKey())
            ->count();

        $Tag->count = $count;
        $Tag->save();

        return $Tag;
    }

    /**
     * @param Model $Model
     * @return Collection
     */
    public function findAllTaggablesByModel(Model $Model) : Collection
    {
        return $this->Taggable
            ->ofForeign($Model)
            ->get();
    }

    /**
     * Delete all unused tags
     */
    public function deleteUnusedTags()
    {
        $this->Tag
            ->where('count', 0)
            ->delete();
    }

}