<?php

namespace App\Services\Product;

use App\Jobs\SendPriceChangeNotification;
use App\Models\Product;
use App\Services\FileUploaderService;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Log;

class ProductUpdateService
{
    public function update(Product $product, array $data, bool $notifyWhenPriceChanged = true): Product
    {
        $oldPrice = $product->price;
        if (array_key_exists('image', $data) && $data['image'] instanceof UploadedFile) {
            /**
             * @var FileUploaderService $imageUploaderService
             */
            $fileUploaderService = app(FileUploaderService::class);
            $data['image'] = $fileUploaderService->upload($data['image'], $product->id);
        }
        $product->fill($data);
        $product->save();

        if ($notifyWhenPriceChanged && $oldPrice != $product->price) {
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

        return $product;
    }
}
