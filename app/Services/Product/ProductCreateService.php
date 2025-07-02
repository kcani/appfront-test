<?php

namespace App\Services\Product;

use App\Models\Product;
use App\Services\FileUploaderService;
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
        $file = null;
        if (array_key_exists('image', $data) && $data['image'] instanceof UploadedFile) {
            $file = $data['image'];
            unset($data['image']);
        }

        $product->fill($data);
        $product->save();

        if ($file) {
            /**
             * @var FileUploaderService $imageUploaderService
             */
            $fileUploaderService = app(FileUploaderService::class);
            $product->image = $fileUploaderService->upload($file, "products/{$product->id}");
            $product->save();
        }

        return $product;
    }
}
