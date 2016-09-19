<?php
/**
 * Created by PhpStorm.
 * User: jatorresdev
 * Date: 18/09/16
 * Time: 12:45 AM
 */

namespace App\Transformer;

use App\User;
use League\Fractal;


class UserTransformer extends Fractal\TransformerAbstract {
    public function transform(User $user) {
        return [
            'id' => (int) $user->id,
            'name' => $user->name,
            'last_name' => $user->last_name,
            'email' => $user->email,
            'cellphone' => $user->cellphone,
            'telephone' => $user->telephone,
            'photo' => $user->photo
        ];
    }
}