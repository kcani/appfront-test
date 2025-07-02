<?php

namespace App\Jobs;

use App\Services\Product\ProductReadService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Mail;
use App\Mail\PriceChangeNotification;

class SendPriceChangeNotification implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable;

    protected int $productId;
    protected float $oldPrice;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(int $productId, float $oldPrice)
    {
        $this->productId = $productId;
        $this->oldPrice = $oldPrice;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(): void
    {
        $notificationEmail = Config::get('notifications.price_change_email_receiver');
        /**
         * @var ProductReadService $productReadService
         */
        $productReadService = app(ProductReadService::class);
        $product = $productReadService->readById($this->productId);
        Mail::to($notificationEmail)->send(
            new PriceChangeNotification($product, $this->oldPrice)
        );
    }
}
