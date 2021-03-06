<?php
/**
 * Created by PhpStorm.
 * User: jatorresdev
 * Date: 15/09/16
 * Time: 5:15 PM
 */

namespace App;

use Illuminate\Database\Eloquent\Model;

class Publication extends Model {

    protected $table = 'publications';

    protected $fillable = ['title', 'description', 'photo', 'city', 'user_id'];

    /**
     * Get the user for the publication.
     */
    public function user() {
        return $this->belongsTo('App\User');
    }

    /**
     * Get the comments for the publication.
     */
    public function comments() {
        return $this->hasMany('App\Comment');
    }

    protected static function boot() {
        parent::boot();

        static::deleting(function($publication) {
            $publication->comments()->delete();
        });
    }

}