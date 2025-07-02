<?php

namespace App\Services\Product;

use App\Models\Product;
use Illuminate\Http\UploadedFile;

class ProductCreateService
{
    /**
     * Creates a new product entity.
     *
     * @param array $data
     * @return Product
     */
    public function create(array $data): Product
    {
        $product = new Product();
        if (array_key_exists('image', $data) && $data['image'] instanceof UploadedFile) {
            $filename = $data['image']->getClientOriginalExtension();
            $data['image']->move(public_path('uploads'), $filename);
            $data['image'] = 'uploads/' . $filename;
        } else {
            $data['image'] = 'product-placeholder.jpg';
        }

        $product->fill($data);

        $product->save();

        return $product;
    }
}
