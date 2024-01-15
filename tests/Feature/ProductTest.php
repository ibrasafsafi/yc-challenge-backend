<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use App\Repositories\ProductRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ProductTest extends TestCase
{
  use RefreshDatabase, WithFaker;

  /** @var \App\Repositories\ProductRepository */
  private ProductRepository $productRepository;

  protected function setUp(): void
  {
    parent::setUp();

    $this->setUpUser();

    $this->productRepository = new ProductRepository();
  }

  public function test_can_get_all_products()
  {
    Product::factory()->count(5)->create();

    $response = $this->get(route('api.products.index'));

    $response->assertSuccessful()
      ->assertJsonCount(5, 'data');
  }

  /** @test */
  public function can_create_product_using_route()
  {
    $product = Product::factory()->makeOne()->toArray();

    $categories = Category::factory()->count(5)->create();

    $product['categories'] = $categories->map->only(['id'])->toArray();

    $response = $this->post(route('api.products.store'), $product);

    $response->assertStatus(201);

    $this->assertDatabaseHas(Product::class, [
      'name' => $product['name'],
    ]);

    $this->assertDatabaseHas('category_product', [
      'category_id' => $categories->first()->id,
      'product_id' => Product::first()->id,
    ]);
  }

  /** @test */
  public function can_update_product_with_categories_using_route()
  {
    $product = Product::factory()->create();

    $categories = Category::factory()->count(5)->create();

    $product['categories'] = $categories->map->only(['id'])->toArray();

    $product->name = $this->faker->name();

    $response = $this->put(route('api.products.update', $product->id), $product->toArray());

    $response->assertStatus(200);

    $this->assertDatabaseHas(Product::class, [
      'name' => $product->name,
    ]);

    $this->assertDatabaseHas('category_product', [
      'category_id' => $categories->first()->id,
      'product_id' => $product->id,
    ]);
  }

  /** @test */
  public function can_find_product_by_id()
  {
    $product = Product::factory()->create();

    $result = $this->productRepository->find($product->id);

    $this->assertInstanceOf(Product::class, $result);
    $this->assertEquals($product->id, $result->id);

  }

  /** @test */
  public function can_create_product_with_categories()
  {
    $productData = [
      'name' => $this->faker->word,
      'description' => $this->faker->sentence,
      'price' => $this->faker->randomFloat(2, 1, 100),
      'categories' => Category::factory()->count(2)->create()->toArray(),
      'image' => 'https://via.placeholder.com/640x480.png/00ddff?text=voluptatem',
    ];

    $result = $this->productRepository->create($productData);

    $this->assertInstanceOf(Product::class, $result);
    $this->assertDatabaseHas('products', ['id' => $result->id]);
    $this->assertEquals(count($productData['categories']), $result->categories->count());
  }


  /** @test */
  public function can_update_product()
  {
    $product = Product::factory()->create();

    $updatedData = [
      'name' => $this->faker->word,
      'description' => $this->faker->sentence,
      'price' => $this->faker->randomFloat(2, 1, 100),
      'categories' => Category::factory()->count(2)->create()->toArray(),
    ];

    $result = $this->productRepository->update($product, $updatedData);

    $this->assertInstanceOf(Product::class, $result);
    $this->assertEquals($updatedData['name'], $result->name);
    $this->assertEquals(count($updatedData['categories']), $result->categories->count());
  }

  /** @test */
  public function can_delete_product_using_route()
  {
    $product = Product::factory()->create();

    $response = $this->delete(route('api.products.destroy', $product->id));

    $response->assertStatus(200);

    $this->assertDatabaseMissing('products', ['id' => $product->id]);
  }

  /** @test */
  public function can_delete_product()
  {
    $product = Product::factory()->create();

    $result = $this->productRepository->delete($product);

    $this->assertTrue($result);
    $this->assertDatabaseMissing('products', ['id' => $product->id]);
  }

  // this function is called after each test function to clear the database
  protected function tearDown(): void
  {
    parent::tearDown();
  }
}
