<?php
/**
 * Created by PhpStorm.
 * User: jatorresdev
 * Date: 18/09/16
 * Time: 12:45 AM
 */

namespace App\Transformer;

use App\Comment;
use League\Fractal;

class CommentTransformer extends Fractal\TransformerAbstract {
    /**
     * List of resources possible to include
     *
     * @var array
     */

    /**
     * Turn this item object into a generic array
     *
     * @return array
     */
    public function transform(Comment $comment) {
        return [
            'id' => (int) $comment->id,
            'message' => $comment->message,
            'created_at' => $comment->created_at,
            'updated_at' => $comment->updated_at,
            'publication' => [
                'id' => (int) $comment->publication->id,
                'title' => $comment->publication->title,
                'description' => $comment->publication->description,
                'city' => $comment->publication->city,
                'created_at' => $comment->publication->created_at,
            ],
            'user' => [
                'id' => $comment->user->id,
                'name' => $comment->user->name,
                'last_name' => $comment->user->last_name,
                'email' => $comment->user->email,
                'cellphone' => $comment->user->cellphone,
                'telephone' => $comment->user->telephone
            ]

        ];
    }
}