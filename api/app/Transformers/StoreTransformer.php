<?php

namespace App\Transformers;

use League\Fractal\TransformerAbstract;
use App\Models\Store;

class StoreTransformer extends TransformerAbstract
{
    /**
     * List of resources to automatically include
     *
     * @var array
     */
    protected $defaultIncludes = [
        //
    ];
    
    /**
     * List of resources possible to include
     *
     * @var array
     */
    protected $availableIncludes = [
        //
    ];
    
    /**
     * A Fractal transformer.
     *
     * @return array
     */
    public function transform(Store $store)
    {
        return [
            'id' => $store->id,
            'store' => $store->store,
        ];
    }
}
