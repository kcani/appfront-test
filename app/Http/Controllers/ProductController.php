<?php

namespace App\Http\Controllers;

use App\Facades\ExchangeRate;
use App\Models\Product;
use App\Services\Product\ProductReadService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Symfony\Component\HttpKernel\Exception\HttpException;

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
        $products = $this->productReadService->paginate(9, $request->page ?: 1);
        $exchangeRate = ExchangeRate::get();
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
        $exchangeRate = ExchangeRate::get();

        return View::make('products.show', compact('product', 'exchangeRate'));
    }

    /**
     * Get stream response for product image.
     *
     * @param Product $product
     * @return \Symfony\Component\HttpFoundation\StreamedResponse
     */
    public function image(Product $product): \Symfony\Component\HttpFoundation\StreamedResponse
    {
        $imageStreamedResponse = $this->productReadService->readStreamedImage($product);
        if (!$imageStreamedResponse) {
            throw new HttpException(404);
        }

        return $imageStreamedResponse;
    }
}
