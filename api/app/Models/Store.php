<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Store extends Model
{
    use HasFactory;

    public $timestamps = NULL;

    protected $fillable = [
        'store',
    ];

    public function product()
    {
        return $this->hasMany('App\Models\Product');
    }

    public function scopeSearch($query, $word)
    {
        $query->where('store', 'like', "%$word%");
    }

}
