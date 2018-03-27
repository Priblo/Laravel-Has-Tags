<?php

namespace Priblo\LaravelHasTags\Traits;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

use Priblo\LaravelHasTags\Models\Tag;
use Priblo\LaravelHasTags\Models\Taggable;
use Priblo\LaravelHasTags\Models\Related;

use Illuminate\Database\Eloquent\Collection;
use Priblo\LaravelHasTags\Repositories\Interfaces\HasTagsRepositoryInterface;

/**
 * Trait HasTags
 * @package Priblo\LaravelHasTags\Traits
 */
trait HasTags
{

    /**
     * @var HasTagsRepositoryInterface
     */
    private $Decorated = null;

    /**
     * HasSettings constructor.
     */
    public function __construct()
    {
        $this->Decorated = resolve(HasTagsRepositoryInterface::class);
    }

    /**
     * @return MorphToMany
     */
    public function tags() : MorphToMany
    {
        return $this->morphToMany(Tag::class, 'taggable', 'has_tags_taggables');
    }

    /**
     * @param string|null $type
     * @return Collection
     */
    public function tagsWithType(string $type = null) : Collection
    {
        return $this->tags->filter(function (Tag $tag) use ($type) {
            return $tag->type === $type;
        });
    }

    /**
     * Returns an array containing all related tags and related objects
     *
     * @param string $tag_slug
     * @param string|null $type
     * @return Related[]
     */
    public static function getRelatedByTag(string $tag_slug, string $type = null) : array
    {
        $relatedTags = [];

        $modelsCollection = (get_class(new static()))::with('tags')->withAnyTag([$tag_slug], $type)->get();

        $modelsCollection->each( function(Model $Model) use (&$relatedTags, $tag_slug) {
            $Model->tags->each( function(Tag $Tag) use (&$relatedTags, $tag_slug, $Model) {
                if($tag_slug !== $Tag->slug) {
                    if (!isset($relatedTags[$Tag->slug])) {
                        $relatedTags[$Tag->slug] = new Related($Tag);
                    }
                    $relatedTags[$Tag->slug]->addModel($Model);
                }
            });
        });

        return $relatedTags;
    }

    /**
     * @param array $tags
     * @param string|null $type
     * @return Model
     */
    public function tag(array $tags = [], string $type = null) : Model
    {
        if(count($tags) === 0) {
            return $this;
        }

        $created_tags = new Collection();

        foreach($tags as $tag_name) {

            $Tag = $this->Decorated->findOneTagBySlugAndType(str_slug($tag_name), $type);
            if (is_null($Tag)) {
                $Tag = $this->Decorated->createOneTagFromStringForType($tag_name, $type);
            }

            $created_tags->add($Tag);
        }

        $this->tags()->syncWithoutDetaching( $created_tags->pluck('id')->toArray() );

        foreach($created_tags as $Tag) {
            $this->Decorated->updateTagCount($Tag);
        }

        $this->load('tags');
        return $this;
    }

    /**
     * Delete and retag from scratch
     *
     * @param array $tags
     * @param string|null $type
     * @return Model
     */
    public function reTag(array $tags = [], string $type = null) : Model
    {
        $this->unTag($type);
        $this->tag($tags, $type);

        return $this;
    }

    /**
     * @param string|null $type
     * @return Model
     */
    public function unTag(string $type = null) : Model
    {
        $taggables = $this->Decorated->findAllTaggablesByModel($this);

        $taggables->each( function (Taggable $Taggable) use ($type) {
            $Tag = $Taggable->tag;
            if($Tag->type === $type) {
                $Taggable->delete();
                $this->Decorated->updateTagCount($Tag);
            }
        });

        $this->Decorated->deleteUnusedTags();

        $this->load('tags');
        return $this;
    }

    /**
     * @param Builder $query
     * @param array $tags
     * @param string|null $type
     * @return Builder
     */
    public function scopeWithAnyTag(Builder $query, array $tags = [], string $type = null): Builder
    {
        if( count($tags) === 0 ){
            return $query;
        }

        $tags_collection = new Collection();
        foreach($tags as $tag_name) {
            $Tag = $this->Decorated->findOneTagBySlugAndType(str_slug($tag_name), $type);
            $tags_collection->add($Tag);
        }


        return $query->whereHas('tags', function (Builder $query) use ($tags_collection) {
            $query->whereIn('has_tags_tags.id', $tags_collection->pluck('id'));
        });
    }

}
