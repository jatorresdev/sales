<?php
/**
 * Created by PhpStorm.
 * User: jatorresdev
 * Date: 18/09/16
 * Time: 12:45 AM
 */

namespace App\Transformer;

use App\Publication;
use League\Fractal;

class PublicationTransformer extends Fractal\TransformerAbstract {
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
    public function transform(Publication $publication) {
        return [
            'id' => (int) $publication->id,
            'title' => $publication->title,
            'description' => $publication->description,
            'city' => $publication->city,
            'photo' => $publication->photo,
            'created_at' => $publication->created_at,
            'user' => [
                'id' => $publication->user->id,
                'name' => $publication->user->name,
                'last_name' => $publication->user->last_name,
                'email' => $publication->user->email,
                'cellphone' => $publication->user->cellphone,
                'telephone' => $publication->user->telephone,
                'photo' => $publication->user->photo
            ]
        ];
    }
}