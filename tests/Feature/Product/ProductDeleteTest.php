<?php

namespace Tests\Feature\Product;

use App\Models\Product;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\Feature\BaseTest;

class ProductDeleteTest extends BaseTest
{
    private Product $product;
    protected function setUp(): void
    {
        parent::setUp();
        $this->createProduct();
        $this->product = Product::query()->first();
        $this->assertNotNull($this->product);
    }

    private function createProduct(): void
    {
        $file = UploadedFile::fake()->image('test.png');
        $this->actingAs($this->user)
            ->withHeaders(['X-CSRF-TOKEN' => csrf_token()])
            ->post('/admin/products', [
                'name' => 'Product 1',
                'description' => 'Product 1 Desc',
                'price' => 20,
                'image' => $file
            ]);
    }

    public function test_delete_product(): void
    {
        // Check the count.
        $this->assertEquals(1, Product::query()->count());

        // Check that the image is in storage.
        $this->assertTrue(Storage::exists($this->product->image));

        $response = $this->actingAs($this->user)
            ->withHeaders(['X-CSRF-TOKEN' => csrf_token()])
            ->delete("/admin/products/{$this->product->id}");

        $response->assertStatus(302);

        // Check the count is decreased.
        $this->assertEquals(0, Product::query()->count());

        // Check that the image is removed from storage.
        $this->assertFalse(Storage::exists($this->product->image));
    }

    public function test_delete_not_existing_product(): void
    {
        // Check the count.
        $this->assertEquals(1, Product::query()->count());

        // Check that the image is in storage.
        $this->assertTrue(Storage::exists($this->product->image));

        $response = $this->actingAs($this->user)
            ->withHeaders(['X-CSRF-TOKEN' => csrf_token()])
            ->delete("/admin/products/5555");

        $response->assertStatus(404);

        // Check that the count is still the same.
        $this->assertEquals(1, Product::query()->count());

        // Check that the image still exists.
        $this->assertTrue(Storage::exists($this->product->image));
    }
}
