<?php

namespace Tests\Feature\Product;

use App\Models\Product;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\Feature\BaseTest;

class ProductStoreTest extends BaseTest
{
    public function test_create_product(): void
    {
        // Check the product count.
        $this->assertEquals(0, Product::query()->count());

        // Test the product create.
        $response = $this->actingAs($this->user)
            ->withHeaders(['X-CSRF-TOKEN' => csrf_token()])
            ->post('/admin/products', [
                'name' => 'Product 1',
                'description' => 'Product 1 Desc',
                'price' => 20
            ]);

        $this->assertEquals(1, Product::query()->count());
        $product = Product::query()->first();
        $this->assertEquals('Product 1', $product->name);
        $this->assertEquals('Product 1 Desc', $product->description);
        $this->assertEquals(20, $product->price);
        $this->assertNull($product->image);
        $this->assertEquals(Product::DEFAULT_IMAGE_PATH, $product->image_url);

        $response->assertStatus(302);
    }

    public function test_create_product_with_image(): void
    {
        // Check the product count.
        $this->assertEquals(0, Product::query()->count());

        // Test the product create including image.
        $file = UploadedFile::fake()->image('test.png');
        $response = $this->actingAs($this->user)
            ->withHeaders(['X-CSRF-TOKEN' => csrf_token()])
            ->post('/admin/products', [
                'name' => 'Product 1',
                'description' => 'Product 1 Desc',
                'price' => 20,
                'image' => $file
            ]);
        $response->assertStatus(302);

        $this->assertEquals(1, Product::query()->count());

        $product = Product::query()->first();

        $this->assertEquals('Product 1', $product->name);
        $this->assertEquals('Product 1 Desc', $product->description);
        $this->assertEquals(20, $product->price);
        $this->assertEquals(route('products.image', $product->id), $product->image_url);
        $this->assertEquals(Storage::get($product->image), $file->get());

        $responseImage = $this->get("/products/{$product->id}/image");
        $responseImage->assertStatus(200);
        $responseImage->assertHeader('Content-Type', 'image/png');
        $responseImage->assertHeader('Content-Disposition', "inline; filename={$product->id}.png");
    }

    public function test_create_product_with_wrong_inputs(): void
    {
        // Check the product count.
        $this->assertEquals(0, Product::query()->count());

        // Test with missing inputs.
        $response = $this->actingAs($this->user)
            ->withHeaders([
                'X-CSRF-TOKEN' => csrf_token(),
                'Accept' => 'application/json',
            ])
            ->post('/admin/products');

        $this->assertEquals(422, $response->getStatusCode());
        $this->assertEquals(0, Product::query()->count());
        $response = json_decode($response->getContent(), true);
        $this->assertEquals('The name field is required.', $response['errors']['name'][0]);
        $this->assertEquals('The price field is required.', $response['errors']['price'][0]);

        // Test with wrong inputs.
        $response = $this->actingAs($this->user)
            ->withHeaders([
                'X-CSRF-TOKEN' => csrf_token(),
                'Accept' => 'application/json',
            ])
            ->post('/admin/products', [
                'name' => 'Product 1',
                'image' => 'wrong input',
                'price' => 'wrong input'
            ]);

        $this->assertEquals(422, $response->getStatusCode());
        $this->assertEquals(0, Product::query()->count());
        $response = json_decode($response->getContent(), true);
        $this->assertEquals('The price field must be a number.', $response['errors']['price'][0]);
        $this->assertEquals('The image field must be an image.', $response['errors']['image'][0]);
    }

    public function test_store_with_not_logged_in_user(): void
    {
        auth()->logout();
        // Test the product create including image.
        $file = UploadedFile::fake()->image('test.png');
        $response = $this
            ->withHeaders([
                'X-CSRF-TOKEN' => csrf_token(),
                'Accept' => 'application/json',
            ])
            ->post('/admin/products', [
                'name' => 'Product 1',
                'description' => 'Product 1 Desc',
                'price' => 20,
                'image' => $file
            ]);

        $response->assertStatus(401);
    }
}
