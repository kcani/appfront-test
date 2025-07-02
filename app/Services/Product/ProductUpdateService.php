<?php

namespace App\Services\Product;

use App\Jobs\SendPriceChangeNotification;
use App\Models\Product;
use App\Services\FileUploaderService;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Log;

class ProductUpdateService
{
    /**
     * Updates an existing product entity.
     *
     * @param Product $product
     * @param array $data
     * @param bool $notifyWhenPriceChanged
     * @return Product
     */
    public function update(Product $product, array $data, bool $notifyWhenPriceChanged = true): Product
    {
        $oldPrice = $product->price;
        if (array_key_exists('image', $data) && $data['image'] instanceof UploadedFile) {
            /**
             * @var FileUploaderService $imageUploaderService
             */
            $fileUploaderService = app(FileUploaderService::class);
            $data['image'] = $fileUploaderService->upload($data['image'], "products/{$product->id}");
        }
        $product->fill($data);
        $product->save();

        if ($notifyWhenPriceChanged && $oldPrice != $product->price) {
            $this->notifyPriceChange($product, $oldPrice);
        }

        return $product;
    }

    /**
     * Send the email notification for the price change to the admin.
     *
     * @param Product $product
     * @param float $oldPrice
     * @return void
     */
    private function notifyPriceChange(Product $product, float $oldPrice): void
    {
        try {
            SendPriceChangeNotification::dispatch($product->id, $oldPrice);
        } catch (\Exception $e) {
            Log::error('Failed to dispatch price change notification: ' . $e->getMessage());
        }
    }
}
