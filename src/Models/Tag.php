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
}
