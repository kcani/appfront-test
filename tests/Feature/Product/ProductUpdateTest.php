<?php

namespace Tests\Feature\Product;

use App\Models\Product;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\Feature\BaseTest;

class ProductUpdateTest extends BaseTest
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

    /**
     * @throws FileNotFoundException
     */
    private function performUpdateTest(array $data): void
    {
        $updateUrl = "/admin/products/{$this->product->id}";
        $response = $this->actingAs($this->user)
            ->withHeaders(['X-CSRF-TOKEN' => csrf_token()])
            ->patch($updateUrl, $data);

        $response->assertStatus(302);
        $this->assertEquals(1, Product::query()->count());
        $this->product->refresh();
        foreach ($data as $field => $value) {
            if ($field == 'image') {
                /**
                 * @var UploadedFile $value
                 */
                $this->assertEquals(Storage::get($this->product->image), $value->get());
            } else {
                $this->assertEquals($value, $this->product->{$field});
            }
        }
    }

    /**
     * @throws FileNotFoundException
     */
    public function test_update_product(): void
    {
        // Check that there's a single entity.
        $this->assertEquals(1, Product::query()->count());

        // Update name
        $this->performUpdateTest([
            'name' => 'Product 1 Updated',
        ]);

        // Update description
        $this->performUpdateTest([
            'description' => 'Product 1 Description Updated',
        ]);

        // Update price
        $this->performUpdateTest([
            'price' => 50,
        ]);

        // Update image
        $this->performUpdateTest([
            'image' => UploadedFile::fake()->image('test1.png'),
        ]);

        // Update multiple
        $this->performUpdateTest([
            'name' => 'Product 1 Updated Again',
            'description' => 'Product 1 Description Updated Again',
            'price' => 80,
            'image' => UploadedFile::fake()->image('test2.png'),
        ]);
    }

    public function test_update_product_no_data(): void
    {
        // Check the product count.
        $this->assertEquals(1, Product::query()->count());

        // Test with missing inputs.
        $response = $this->actingAs($this->user)
            ->withHeaders([
                'X-CSRF-TOKEN' => csrf_token(),
                'Accept' => 'application/json',
            ])
            ->patch("/admin/products/{$this->product->id}");
        $this->assertEquals(1, Product::query()->count());

        // Check old and updated model to be with the same property values,
        $attributesToCheck = [
            'name',
            'price',
            'description',
            'image',
        ];
        $product = Product::query()->first();
        foreach ($attributesToCheck as $attribute) {
            $this->assertEquals($product->{$attribute}, $this->product->{$attribute});
        }

        $response->assertStatus(302);
    }

    public function test_update_product_wrong_data()
    {
        // Check the product count.
        $this->assertEquals(1, Product::query()->count());

        // Test with missing inputs.
        $response = $this->actingAs($this->user)
            ->withHeaders([
                'X-CSRF-TOKEN' => csrf_token(),
                'Accept' => 'application/json',
            ])
            ->patch("/admin/products/{$this->product->id}", [
                'name' => null,
                'price' => null,
                'image' => 'test',
            ]);
        $response->assertStatus(422);
        $responseObject = json_decode($response->getContent(), true);
        $this->assertEquals('The name field is required.', $responseObject['errors']['name'][0]);
        $this->assertEquals('The price field is required.', $responseObject['errors']['price'][0]);
        $this->assertEquals('The image field must be an image.', $responseObject['errors']['image'][0]);

        $response = $this->actingAs($this->user)
            ->withHeaders([
                'X-CSRF-TOKEN' => csrf_token(),
                'Accept' => 'application/json',
            ])
            ->patch("/admin/products/{$this->product->id}", [
                'price' => 'test',
            ]);
        $response->assertStatus(422);
        $responseObject = json_decode($response->getContent(), true);
        $this->assertEquals('The price field must be a number.', $responseObject['errors']['price'][0]);
    }
}
