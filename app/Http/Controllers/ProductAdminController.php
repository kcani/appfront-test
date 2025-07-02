<?php

namespace App\Http\Controllers;

use App\Http\Requests\Product\ProductStoreRequest;
use App\Http\Requests\Product\ProductUpdateRequest;
use App\Services\Product\ProductCreateService;
use App\Services\Product\ProductReadService;
use App\Services\Product\ProductUpdateService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Product;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use App\Jobs\SendPriceChangeNotification;
use Illuminate\Support\Facades\View;

class ProductAdminController extends Controller
{
    public function __construct(
        private readonly ProductReadService $productReadService,
        private readonly ProductCreateService $productCreateService,
        private readonly ProductUpdateService $productUpdateService
    )
    {
    }

    /**
     * Returns the table view with products.
     *
     * @param Request $request
     * @return \Illuminate\Contracts\View\View
     */
    public function index(Request $request): \Illuminate\Contracts\View\View
    {
        $products = $this->productReadService->paginate(10, $request->page ?: 1);

        return View::make('admin.products.list', compact('products'));
    }

    /**
     * Returns the view with the form to add a new product.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function create(): \Illuminate\Contracts\View\View
    {
        return View::make('admin.products.create');
    }

    /**
     * Save a new product entity.
     *
     * @param ProductStoreRequest $productStoreRequest
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(ProductStoreRequest $productStoreRequest): \Illuminate\Http\RedirectResponse
    {
        $this->productCreateService->create($productStoreRequest->validated());

        return redirect()->route('admin.products.index')->with('success', 'Product added successfully');
    }

    /**
     * Returns the view to update an existing product.
     *
     * @param Product $product
     * @return \Illuminate\Contracts\View\View
     */
    public function edit(Product $product): \Illuminate\Contracts\View\View
    {
        return View::make('admin.products.edit', compact('product'));
    }

    /**
     * Perform the update of an existing product,
     *
     * @param ProductUpdateRequest $productUpdateRequest
     * @param Product $product
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(ProductUpdateRequest $productUpdateRequest, Product $product): \Illuminate\Http\RedirectResponse
    {
        $this->productUpdateService->update($product, $productUpdateRequest->validated());

        return redirect()->route('admin.products.index')->with('success', 'Product updated successfully');
    }

    /**
     * Perform the delete of the existing product.
     *
     * @param Product $product
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Product $product): \Illuminate\Http\RedirectResponse
    {
        $product->delete();

        return redirect()->route('admin.products.index')->with('success', 'Product deleted successfully');
    }
}
