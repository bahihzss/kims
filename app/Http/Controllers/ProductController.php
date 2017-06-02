<?php

namespace App\Http\Controllers;

use App\Product;
use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $p = $request->get('p');
        Paginator::currentPageResolver(function() use ($p) {
            return $p;
        });
        $purchase_date_start = $request->get('purchase_date_start');
        $purchase_date_end = $request->get('purchase_date_end');
        $only_stock = $request->get('only_stock');

        $products = Product::orderBy('id', 'desc');
        if (!empty($purchase_date_start)) {
            $products->where('purchase_date', '>=', $purchase_date_start);
        }
        if (!empty($purchase_date_end)) {
            $products->where('purchase_date', '<=', $purchase_date_end);
        }
        if ($only_stock) {
            $products->where(function($query) {
                $query->where('category_id', 1)->orWhere('category_id', 2);
            });
            $products->where(function($query) {
                $query->whereNull('sale_date')->orWhere('sale_date', '0000-00-00');
            });
        }

        return $products->paginate(50)->toJson();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        Product::create($request->all());
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $previous = Product::where('id', '<', $id)->max('id');
        $next = Product::where('id', '>', $id)->min('id');
        $product = Product::find($id);
        return collect(['prev' => $previous, 'next' => $next, 'product' => $product])->toJson();
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        Product::find($id)->fill($request->all())->save();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Product::find($id)->delete();
    }

    /**
     * Output report.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function report()
    {
        $reports = collect();
        for($i=12; $i>=0; $i--) {
            $month = date('m') - $i > 0 ? date('m') - $i : date('m') - $i + 12;
            $year =  date('m') >= $month ? date('Y') : date('Y') - 1;
            $reports[] = $this->getReport($year, $month);
        }

        $totalReport = $this->getTotalReport();

        $products = Product::query();
        $products->where(function($query) {
            $query->where('category_id', 1)->orWhere('category_id', 2);
        });
        $products->whereNull('sale_date');

        return view('report', ['products' => $products, 'reports' => $reports, 'totalReport' => $totalReport]);
    }

    protected function getReport($year,$month)
    {
        $purchases = $purchase_price = Product::query()->whereYear('purchase_date', '=', $year)->whereMonth('purchase_date', '=', $month);
        $purchase_price = $purchases->sum('purchase_price');
        $purchase_length = $purchases->where('category_id', 1)->count();
        $sales = Product::query()->whereYear('sale_date', '=', $year)->whereMonth('sale_date', '=', $month);
        $sale_price = $sales->sum('sale_price');
        $sale_length = $sales->where('category_id', 1)->count();

        return [
            'month' => "{$year}/{$month}",
            'purchase_price' => $purchase_price,
            'purchase_length' => $purchase_length,
            'sale_price' => $sale_price,
            'sale_length' => $sale_length,
            'benefit' => $sale_price - $purchase_price
        ];
    }

    protected function getTotalReport()
    {
        $total = [];

        $total['purchase_price'] = Product::all()->sum('purchase_price');
        $total['sale_price'] = Product::all()->sum('sale_price');
        $total['benefit'] = $total['sale_price'] - $total['purchase_price'];
        $total['purchase_length'] = Product::query()->where('category_id', 1)->count();
        $total['sale_length'] = Product::query()->where('category_id', 1)->whereNotNull('sale_date')->count();

        return $total;
    }
}
