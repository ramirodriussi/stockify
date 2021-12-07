<?php

namespace App\Transformers;

use League\Fractal\TransformerAbstract;
use App\Models\User;

class UserTransformer extends TransformerAbstract
{
    /**
     * A Fractal transformer.
     *
     * @return array
     */

    public function __construct($fields = null)
    {
        $this->fields = $fields;
    }

    public function transform(User $user)
    {

        return $this->transformWithFieldFilter([
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'role' => $user->role,
            'logo' => (isset($user->logo->image)) ? env('APP_URL') . $user->logo->image : null,
        ]);

    }

    protected function transformWithFieldFilter($data)
    {
        if (is_null($this->fields)) {
            return $data;
        }

        return array_intersect_key($data, array_flip((array) $this->fields));
    }


}
