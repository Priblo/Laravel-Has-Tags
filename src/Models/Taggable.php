<?php namespace Priblo\LaravelHasTags\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Taggable
 * @package Priblo\LaravelHasTags\Models
 */
class Taggable extends Model
{
	protected $table = 'has_tags_taggables';
	protected $primaryKey = 'id';

	public $timestamps = false;

	/**
	 * Morph to the tag
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\MorphTo
	 */
	public function taggable()
	{
		return $this->morphTo();
	}

	/**
	 * Get instance of tag linked to the tagged value
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
	 */
	public function tag()
	{
		return $this->belongsTo(Tag::class, 'tag_id', 'id');
	}

    /**
     * @param $query
     * @param Model $Model
     * @return mixed
     */
    public function scopeOfForeign($query, Model $Model)
    {
        return $query->where([
            'taggable_id' => $Model->getKey(),
            'taggable_type' => get_class($Model)
        ]);
    }

}
