<?php

namespace App\Transformers;

use League\Fractal\TransformerAbstract;
use App\Models\Sale;

class SaleTransformer extends TransformerAbstract
{
    /**
     * List of resources to automatically include
     *
     * @var array
     */
    protected $defaultIncludes = [
        'products',
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
    public function transform(Sale $sale)
    {
        return [
            'id' => $sale->id,
            'sale_id' => $sale->sale_id,
            // 'products' => $sale->product,
            // 'total_price' => $sale->total_price,
            'payment_type' => $sale->payment_type,
            'created_at' => $sale->created_at->format('d-m-Y h:m'),
        ];
    }

    public function includeProducts(Sale $sale)
    {

        $fields = ['id','product','code','price','quantity'];

        $q = $sale->product;

        $transformer = new ProductTransformer($fields);
        $transformer->setSaleId($sale->id);
        return $this->collection($q, $transformer);

    }

}
