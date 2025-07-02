<?php

namespace App\Http\Controllers;

use App\Libs\ExchangeRateLib;
use Illuminate\Http\Request;
use App\Models\Product;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::all();
        $exchangeRate = ExchangeRateLib::get();

        return view('products.list', compact('products', 'exchangeRate'));
    }

    public function show(Request $request)
    {
        $id = $request->route('product_id');
        $product = Product::find($id);
        $exchangeRate = ExchangeRateLib::get();

        return view('products.show', compact('product', 'exchangeRate'));
    }
}
