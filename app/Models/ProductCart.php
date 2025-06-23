<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductCart extends Model
{
    protected $fillable = ['color','size', 'qty','price','product_id', 'user_id'];

    public function product()
    {
        return $this->belongsTo('App\Models\Product');
    }
}
