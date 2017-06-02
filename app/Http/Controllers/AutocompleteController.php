<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Product;

class AutocompleteController extends Controller
{
    protected $request;
    protected $product;

    public function __construct(Request $request, Product $product)
    {
        $this->request = $request;
        $this->product = $product;
    }

    public function search($type)
    {
        $camelType = camel_case($type);
        return $this->$camelType();
    }

    private function name()
    {
        $text = $this->request->get('text');
        $names = $this->product
            ->where('name', 'like', "%{$text}%")
            ->where(function($query) {
                $query->where('category_id', 1)
                    ->orWhere('category_id', 2);
            })
            ->groupBy('name')
            ->get(['name'])
            ->take(15);

        return $names->map(function($item) {return $item['name'];})->toJson();
    }

    private function purchase()
    {
        $text = $this->request->get('text');
        $purchases = $this->product
            ->where('purchase_place', 'like', "%{$text}%")
            ->groupBy('purchase_place')
            ->get(['purchase_place']);

        return $purchases->map(function($item) {return $item['purchase_place'];})->toJson();
    }

    private function sale()
    {
        $text = $this->request->get('text');
        $sales = $this->product
            ->where('sale_place', 'like', "%{$text}%")
            ->groupBy('sale_place')
            ->get(['sale_place']);

        return $sales->map(function($item) {return $item['sale_place'];})->toJson();
    }
}
