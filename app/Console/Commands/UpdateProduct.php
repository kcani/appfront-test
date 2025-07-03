<?php

namespace App\Console\Commands;

use App\Http\Requests\Product\ProductUpdateRequest;
use App\Services\Product\ProductReadService;
use App\Services\Product\ProductUpdateService;
use Illuminate\Console\Command;
use App\Models\Product;
use Illuminate\Support\Facades\Validator;
use App\Jobs\SendPriceChangeNotification;
use Illuminate\Support\Facades\Log;

class UpdateProduct extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'product:update {id} {--name=} {--description=} {--price=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update a product with the specified details';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(): int
    {
        $validator = Validator::make(
            [
                'name' => trim($this->option('name')),
                'description' => trim($this->option('description')),
                'price' => $this->option('price'),
            ],
            [
                'name' => 'nullable|min:3|max:255',
                'description' => 'nullable',
                'price' => 'nullable|numeric',
            ]
        );
        if ($validator->fails()) {
            foreach ($validator->getMessageBag()->messages() as $messages) {
                foreach ($messages as $message) {
                    $this->error($message);
                }
            }
            return 0;
        }
        /**
         * @var ProductReadService $productReadService
         * @var ProductUpdateService $productUpdateService
         */
        $productReadService = app(ProductReadService::class);
        $productUpdateService = app(ProductUpdateService::class);
        $product = $productReadService->readById($this->argument('id'));
        if (!$product) {
            $this->error('Wrong product ID inserted.');
            return 0;
        }
        $dataToUpdate = array_filter($validator->getData(), function ($value) {
            return $value;
        });

        $oldPrice = $product->price;
        $priceChanged = isset($dataToUpdate['price']) && $dataToUpdate['price'] != $oldPrice;

        $productUpdateService->update($product, $dataToUpdate);
        if ($priceChanged) {
            $this->info("Price changed from {$oldPrice} to {$product->price}.");
        }

        return 0;
    }
}
