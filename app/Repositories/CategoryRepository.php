<?php

namespace App\Repositories;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Eloquent\Model;

class CategoryRepository
{

  /*
   * @param int $id
   * @return Category
   * */
  public function find(int $id): Category
  {
    return Category::find($id);
  }

  /*
   * @param array<mixed> $data
   * @return Category
   * */
  public function create(array $data) : Category
  {
    return Category::query()->create($data);
  }

  /*
   * @param int|Category $model
   * @param array<mixed> $data
   * @return Category
   * */
  public function update(Category|int $model, array $data) : Category
  {
    if (!($model instanceof Model)) {
      $model = $this->find($model);
    }

    $model->update($data);

    return $model;
  }


  /*
   * @param Product $product
   * @param array<mixed> $categories
   * */
  public function syncProductCategories(Product $product, array $categories): void
  {
    $product->categories()->sync($categories);
  }

  /*
   * @return \Illuminate\Database\Eloquent\Collection<Category>
   * */
  public function all(): \Illuminate\Database\Eloquent\Collection
  {
    return Category::all();
  }


  /*
   * @param int|Category $model
   * @return bool
   * */
  public function delete(Category|int $model) : bool
  {
    if (!($model instanceof Model)) {
      $model = $this->find($model);
    }

    return $model->delete();
  }
}
