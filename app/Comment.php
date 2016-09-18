<?php
/**
 * Created by PhpStorm.
 * User: jatorresdev
 * Date: 18/09/16
 * Time: 1:35 PM
 */

namespace app;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model {
    protected $table = 'comments';

    protected $fillable = ['comment', 'publication_id', 'user_id'];

    /**
     * Get the user for the comment.
     */
    public function user() {
        return $this->hasOne('App\User');
    }

    /**
     * Get the publication that owns the comment.
     */
    public function publication() {
        return $this->belongsTo('App\Publication');
    }
}