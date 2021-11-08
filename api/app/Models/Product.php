<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'product',
        'price',
        'stock',
        'stock_notification_below',
        'store_id',
    ];

    public function store()
    {
        return $this->belongsTo('App\Models\Store');
    }

}
