<?php

namespace App\Services\Product;

use App\Models\Product;
use Illuminate\Support\Facades\Storage;

class ProductDeleteService
{
    public function delete(Product $product): void
    {
        $product->delete();

        // Remove the image.
        if ($product->image && Storage::exists($product->image)) {
            Storage::delete($product->image);
        }
    }
}
