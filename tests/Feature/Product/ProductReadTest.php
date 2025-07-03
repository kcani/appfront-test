<?php

namespace Tests\Feature\Product;

use App\Facades\ExchangeRate;
use App\Models\Product;
use Illuminate\Http\UploadedFile;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Config;
use Symfony\Component\Intl\Currencies;
use Tests\Feature\BaseTest;

class ProductReadTest extends BaseTest
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->createProducts();
    }

    private function createProducts(): void
    {
        for ($i = 1; $i <= 20; $i++) {
            $this->actingAs($this->user)
                ->withHeaders(['X-CSRF-TOKEN' => csrf_token()])
                ->post('/admin/products', [
                    'name' => "Product {$i}",
                    'description' => "Product {$i} Desc",
                    'price' => $i * 10,
                    'image' => UploadedFile::fake()->image("test-{$i}.png")
                ]);
        }
    }

    public function test_get_products_list_admin_page(): void
    {
        $response = $this->actingAs($this->user)->get('admin/products');

        $response->assertStatus(200);

        /**
         * @var LengthAwarePaginator $products
         */
        $products = $response->viewData('products');
        $this->assertInstanceOf(LengthAwarePaginator::class, $products);
        $this->assertCount(10, $products->items());
        $this->assertEquals(20, $products->total());
    }

    public function test_get_product_admin_edit_page(): void
    {
        $product = Product::query()->first();

        $response = $this->actingAs($this->user)->get("admin/products/{$product->id}/edit");

        $response->assertStatus(200);

        /**
         * @var Product $viewProduct
         */
        $viewProduct =  $response->viewData('product');
        $this->assertEquals($product->name, $viewProduct->name);
        $this->assertEquals($product->description, $viewProduct->description);
        $this->assertEquals($product->price, $viewProduct->price);
        $this->assertEquals($product->image, $viewProduct->image);
    }

    public function test_get_product_admin_edit_page_with_not_existing_product(): void
    {
        $response = $this->actingAs($this->user)->get("admin/products/2000/edit");
        $response->assertStatus(404);
    }

    public function test_get_product_admin_create_page(): void
    {
        $response = $this->actingAs($this->user)->get("admin/products/create");
        $response->assertStatus(200);
    }

    private function mockExchangeRate(): void
    {
        $from = strtoupper(Config::get('external.exchange-rate.base_from_currency'));
        $to = strtoupper(Config::get('external.exchange-rate.base_to_currency'));
        ExchangeRate::shouldReceive('get')
            ->once()
            ->andReturn([
                'value' => 0.8,
                'from' => $from,
                'from_symbol' => Currencies::getSymbol($from),
                'to' => $to,
                'to_symbol' => Currencies::getSymbol($to)
            ]);
    }

    public function test_get_products_list_guest_page(): void
    {
        $this->mockExchangeRate();

        $response = $this->get('/');

        $response->assertStatus(200);

        /**
         * @var LengthAwarePaginator $products
         */
        $products = $response->viewData('products');
        $this->assertInstanceOf(LengthAwarePaginator::class, $products);
        $this->assertCount(9, $products->items());
        $this->assertEquals(20, $products->total());

        // The value coming from mocking.
        $exchangeRate = $response->viewData('exchangeRate');
        $this->assertEquals(0.8, $exchangeRate['value']);
    }

    public function test_get_product_guest_page(): void
    {
        $this->mockExchangeRate();

        $product = Product::query()->first();

        $response = $this->get("/products/{$product->id}");

        $response->assertStatus(200);

        /**
         * @var Product $viewProduct
         */
        $viewProduct =  $response->viewData('product');
        $this->assertEquals($product->name, $viewProduct->name);
        $this->assertEquals($product->description, $viewProduct->description);
        $this->assertEquals($product->price, $viewProduct->price);
        $this->assertEquals($product->image, $viewProduct->image);

        // The value coming from mocking.
        $exchangeRate = $response->viewData('exchangeRate');
        $this->assertEquals(0.8, $exchangeRate['value']);
    }

    public function test_get_product_guest_page_with_not_existing_product(): void
    {
        $response = $this->get("/products/2000");

        $response->assertStatus(404);
    }

    public function test_get_product_image(): void
    {
        $product = Product::query()->first();

        $response = $this->get("/products/{$product->id}/image");
        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'image/png');
        $response->assertHeader('Content-Disposition', "inline; filename={$product->id}.png");
    }

    public function test_read_endpoints_with_not_logged_in_user(): void
    {
        // Logout to destroy the session.
        auth()->logout();
        $response = $this
            ->withHeaders([
                'Accept' => 'application/json'
            ])
            ->get('admin/products');
        $response->assertStatus(401);

        $product = Product::query()->first();

        $response = $this
            ->withHeaders([
                'Accept' => 'application/json'
            ])
            ->get("admin/products/{$product->id}/edit");
        $response->assertStatus(401);

        $response = $this
            ->withHeaders([
                'Accept' => 'application/json'
            ])
            ->get("admin/products/create");
        $response->assertStatus(401);
    }
}
