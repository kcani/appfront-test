<?php

namespace App\Services\Product;

use App\Models\Product;

class ProductDeleteService
{
    public function delete(Product $product): void
    {
        $productImagePath = $product->image;
        $product->delete();

        // Remove the image.
        if ($productImagePath != Product::DEFAULT_IMAGE_NAME && file_exists($productImagePath)) {
            unlink($productImagePath);
        }
    }
}
