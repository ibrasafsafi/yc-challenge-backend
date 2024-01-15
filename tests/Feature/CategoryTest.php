<?php


use App\Models\Category;
use App\Repositories\CategoryRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class
CategoryTest extends TestCase
{
  use RefreshDatabase, WithFaker;

  /** @var \App\Repositories\CategoryRepository */
  private CategoryRepository $categoryRepository;

  protected function setUp(): void
  {
    parent::setUp();

    $this->setUpUser();

    $this->categoryRepository = new CategoryRepository();
  }

  public function test_can_get_all_categories()
  {
    Category::factory()->count(5)->create();

    $response = $this->get(route('api.categories.index'));

    $response->assertSuccessful()
      ->assertJsonCount(5, 'data');
  }

  /** @test */
  public function can_create_category_using_route()
  {
    $category = Category::factory()->makeOne()->toArray();

    $response = $this->post(route('api.categories.store'), $category);

    $response->assertStatus(201);

    $this->assertDatabaseHas(Category::class, [
      'name' => $category['name'],
    ]);
  }

  /** @test */
  public function can_update_category_using_route()
  {
    $category = Category::factory()->create();

    $category->name = $this->faker->name();

    $response = $this->put(route('api.categories.update', $category->id), $category->toArray());

    $response->assertStatus(200);

    $this->assertDatabaseHas(Category::class, [
      'name' => $category->name,
    ]);
  }

  /** @test */
  public function can_delete_category_using_route()
  {
    $category = Category::factory()->create();

    $response = $this->delete(route('api.categories.destroy', $category->id));

    $response->assertStatus(200);

    $this->assertDatabaseMissing(Category::class, [
      'id' => $category->id,
    ]);
  }

  /** @test */
  public function can_find_category_using_route()
  {
    $category = Category::factory()->create();

    $response = $this->get(route('api.categories.show', $category->id));

    $response->assertSuccessful()
      ->assertJson([
        'data' => [
          'id' => $category->id,
          'name' => $category->name,
        ]
      ]);
  }


  // this function is called after each test function to clear the database
  protected function tearDown(): void
  {
    parent::tearDown();
  }
}
