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

    public function scopeSearch($query, $word)
    {
        $query->where('product', 'like', "%$word%");
    }

    public function scopeStore($query, $id)
    {

        if($id){

            $query->whereHas('store', function($q) use ($id){
                $q->where('id', $id);
            });

        }

        return $query;

    }

}
