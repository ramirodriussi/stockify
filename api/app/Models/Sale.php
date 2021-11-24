<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    use HasFactory;

    const UPDATED_AT = NULL;

    protected $fillable = [
        'sale_id',
        'payment_type',
    ];

    public function product()
    {
        return $this->belongsToMany('App\Models\Product')->withPivot('product','price','quantity');
    }

    public function scopeSearch($query, $word)
    {
        $query->where('sale_id', 'like', "%$word%");
    }

}
