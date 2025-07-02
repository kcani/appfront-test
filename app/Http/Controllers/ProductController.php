<?php

namespace App\Http\Controllers;

use App\Libs\ExchangeRateLib;
use App\Models\Product;
use App\Services\Product\ProductReadService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;

class ProductController extends Controller
{
    public function __construct(private readonly ProductReadService $productReadService)
    {}

    /**
     * Get the product list view.
     *
     * @param Request $request
     * @return \Illuminate\Contracts\View\View
     */
    public function index(Request $request): \Illuminate\Contracts\View\View
    {
        $products = $this->productReadService->paginate(9, $request->page);
        $exchangeRate = ExchangeRateLib::get();
        return View::make('products.list', compact('products', 'exchangeRate'));
    }

    /**
     * Get a single product details view.
     *
     * @param Product $product
     * @return \Illuminate\Contracts\View\View
     */
    public function show(Product $product): \Illuminate\Contracts\View\View
    {
        $exchangeRate = ExchangeRateLib::get();

        return View::make('products.show', compact('product', 'exchangeRate'));
    }
}
