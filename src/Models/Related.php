<?php namespace Priblo\LaravelHasTags\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Related
 * @package Priblo\LaravelHasTags\Models
 */
class Related
{
    /**
     * @var Tag
     */
    public $Tag;

    /**
     * @var Collection
     */
    public $relatedModels;

    /**
     * Related constructor.
     * @param Tag $Tag
     */
    public function __construct(Tag $Tag)
    {
        $this->Tag = $Tag;
        $this->relatedModels = new Collection();
    }

    /**
     * @param Model $Model
     */
    public function addModel(Model $Model)
    {
        $this->relatedModels->add($Model);
    }
}
