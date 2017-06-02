<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'name',
        'purchase_date',
        'purchase_price',
        'purchase_place',
        'sale_date',
        'sale_price',
        'sale_place',
        'category_id',
        'exhibition_date'
    ];
}
