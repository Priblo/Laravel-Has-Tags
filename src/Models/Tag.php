<?php namespace Priblo\LaravelHasTags\Models;

use Illuminate\Database\Eloquent\Model as Eloquent;

/**
 * Class Tag
 * @package Priblo\LaravelHasTags\Models
 */
class Tag extends Eloquent
{
    protected $table = 'has_tags_tags';
    protected $primaryKey = 'id';

    protected $softDelete = false;

    /**
     * @param string $slug
     * @return int
     */
    public static function readCountBySlugAndType(string $slug, $type = null) :int
    {
        $Tag = static::where(['slug' => $slug, 'type' => $type])->first();
        if(is_null($Tag)){
            return 0;
        }

        return $Tag->count;
    }
}
