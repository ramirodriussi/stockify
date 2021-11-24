<?php

namespace App\Transformers;

use League\Fractal\TransformerAbstract;
use App\Models\Product;

class ProductTransformer extends TransformerAbstract
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
    
    public function __construct($fields = null)
    {
        $this->fields = $fields;
    }

    /**
     * A Fractal transformer.
     *
     * @return array
     */
    public function transform(Product $product)
    {

        return $this->transformWithFieldFilter([
            'id' => $product->id,
            'product' => $product->product,
            'code' => $product->code,
            'stock' => $product->stock,
            'stock_notification_below' => $product->stock_notification_below,
            'price' => $product->price,
            'store' => $product->store,
            'quantity' => (in_array('quantity', $this->fields)) ? $product->sale->where('id', $this->sale_id)->pluck('pivot')->first()->quantity : null,
        ]);

    }

    protected function transformWithFieldFilter($data)
    {
        if (is_null($this->fields)) {
            return $data;
        }

        return array_intersect_key($data, array_flip((array) $this->fields));
    }

    public function setSaleId($id)
    {
        $this->sale_id = $id;
    }

}
