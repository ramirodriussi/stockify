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
    public function transform(Sale $sale)
    {
        return [
            'id' => $sale->id,
            'sale_id' => $sale->sale_id,
            'products' => json_decode($sale->products, true),
            'total_price' => $sale->total_price,
            'payment_type' => $sale->payment_type,
            'created_at' => $sale->created_at->format('d-m-Y h:m'),
        ];
    }
}
