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

    public function findAllTaggedModelsByModelAndSlugAndType(Model $Model, string $tag_slug, string $type = null) : Collection
    {
        return (get_class($Model))::with('tags')->withAnyTag([$tag_slug], $type)->get();
    }

    public function findOneTagBySlugAndType(string $tag_slug, string $type = null) : ?Tag
    {
        return $this->Tag
            ->where(['slug'=> $tag_slug, 'type' => $type])
            ->first()
            ;
    }

    public function createOneTagFromStringForType(string $tag_name, string $type = null) : Tag
    {
        $Tag = new Tag();
        $Tag->type = $type;
        $Tag->name = $tag_name;
        $Tag->slug = str_slug($tag_name);
        $Tag->count = 1;
        $Tag->save();

        return $Tag;
    }

    public function updateTagCount(Tag $Tag) : Tag
    {
        $count = $this->Taggable
            ->where('tag_id', $Tag->getKey())
            ->count();

        $Tag->count = $count;
        $Tag->save();

        return $Tag;
    }

    public function findAllTaggablesByModel(Model $Model) : Collection
    {
        return $this->Taggable
            ->ofForeign($Model)
            ->get();
    }

    public function deleteUnusedTags() : void
    {
        $this->Tag
            ->where('count', 0)
            ->delete();
    }

}