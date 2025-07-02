<?php

namespace App\Http\Controllers;

use App\Http\Requests\Product\ProductStoreRequest;
use App\Http\Requests\Product\ProductUpdateRequest;
use App\Services\Product\ProductReadService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Product;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use App\Jobs\SendPriceChangeNotification;
use Illuminate\Support\Facades\View;

class ProductAdminController extends Controller
{
    public function __construct(private readonly ProductReadService $productReadService)
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
        $product = Product::create([
            'name' => $productStoreRequest->name,
            'description' => $productStoreRequest->description,
            'price' => $productStoreRequest->price
        ]);

        if ($productStoreRequest->hasFile('image')) {
            $file = $productStoreRequest->file('image');
            $filename = $file->getClientOriginalExtension();
            $file->move(public_path('uploads'), $filename);
            $product->image = 'uploads/' . $filename;
        } else {
            $product->image = 'product-placeholder.jpg';
        }

        $product->save();

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
        // Store the old price before updating
        $oldPrice = $product->price;

        $product->fill($productUpdateRequest->validated());
        $product->save();

        if ($productUpdateRequest->hasFile('image')) {
            $file = $productUpdateRequest->file('image');
            $filename = $file->getClientOriginalExtension();
            $file->move(public_path('uploads'), $filename);
            $product->image = 'uploads/' . $filename;
        }

        $product->save();

        // Check if price has changed
        if ($oldPrice != $product->price) {
            // Get notification email from env
            $notificationEmail = env('PRICE_NOTIFICATION_EMAIL', 'admin@example.com');

            try {
                SendPriceChangeNotification::dispatch(
                    $product,
                    $oldPrice,
                    $product->price,
                    $notificationEmail
                );
            } catch (\Exception $e) {
                 Log::error('Failed to dispatch price change notification: ' . $e->getMessage());
            }
        }

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
