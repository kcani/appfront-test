<?php

namespace App\Services\Product;

use App\Models\Product;
use \Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Storage;

class ProductReadService
{
    /**
     * Get the paginated result for the Product records.
     *
     * @param int $perPage
     * @param int $page
     * @return LengthAwarePaginator
     */
    public function paginate(int $perPage = 9, int $page = 1): LengthAwarePaginator
    {
        return Product::query()->paginate($perPage, page: $page);
    }

    /**
     * Get the product entity by id.
     *
     * @param int $id
     * @return Product|null
     */
    public function readById(int $id): Product|null
    {
        return Product::query()->find($id);
    }

    public function readStreamedImage(Product $product): \Symfony\Component\HttpFoundation\StreamedResponse|null
    {
        if ($product->image && Storage::exists($product->image)) {
            return Storage::response($product->image);
        }

        return null;
    }
}
