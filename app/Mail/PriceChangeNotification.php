<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use App\Models\Product;

class PriceChangeNotification extends Mailable
{
    public Product $product;
    public float $oldPrice;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Product $product, float $oldPrice)
    {
        $this->product = $product;
        $this->oldPrice = $oldPrice;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build(): self
    {
        return $this
            ->subject('Product Price Change Notification')
            ->view('emails.price-change');
    }
}
