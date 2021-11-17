<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    use HasFactory;

    const UPDATED_AT = NULL;

    protected $fillable = [
        'products',
        'total_price',
        'payment_type',
        'sale_id',
    ];

    public function product()
    {
        return $this->hasMany('App\Models\Product');
    }

    public function scopeSearch($query, $word)
    {
        $query->where('sale_id', 'like', "%$word%");
    }

}
