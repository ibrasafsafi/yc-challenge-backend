<?php

namespace App\Repositories;

use App\Models\Product;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class ProductRepository
{
  private const ALLOWED_SORT_FIELDS = [
    'id',
    'name',
    'description',
    'price',
  ];

  private const ALLOWED_SEARCH_FIELDS = [
    'name',
    'description',
  ];

  private const ALLOWED_FILTER_FIELDS = [
    'id',
    'name',
    'description',
    'price',
  ];

  private const DEFAULT_INCLUDES = [
    'categories',
  ];

  protected CategoryRepository $categoryRepository;

  public function __construct(CategoryRepository $categoryRepository)
  {
    $this->categoryRepository = $categoryRepository;
  }

  /**
   * @param string|null $sort
   * @param string $direction
   * @param string|null $search
   * @param array<mixed> $filters
   * @param int $perPage
   * @param bool $defaultIncludes
   * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator<Product>
   **/
  public function all(string $sort = null, string $direction = 'asc', string $search = null, array $filters = [], int $perPage = 30, bool $defaultIncludes = true): \Illuminate\Contracts\Pagination\LengthAwarePaginator
  {
    $sort = $this->validateSortField($sort);
    $direction = $this->validateSortDirection($direction);
    $search = $this->sanitizeSearchField($search);
    $filters = $this->sanitizeFilters($filters);

    $query = Product::query()->with($defaultIncludes ? self::DEFAULT_INCLUDES : []);

    if ($sort) {
      $query->orderBy($sort, $direction);
    }

    if ($search) {
      $query->where(function ($query) use ($search) {
        foreach (self::ALLOWED_SEARCH_FIELDS as $field) {
          $query->orWhere($field, 'like', "%{$search}%");
        }
      });
    }

    foreach ($filters as $field => $value) {
      if (in_array($field, self::ALLOWED_FILTER_FIELDS)) {
        $query->where($field, $value);
      }
    }

    return $query->paginate($perPage);
  }

  /**
   * @param int $id
   * @return Product
   **/
  public function find(int $id): Product
  {
    return Product::with(self::DEFAULT_INCLUDES)->find($id);
  }

  /**
   * @param array<mixed> $data
   * @return Product
   **/
  public function create(array $data): Product
  {
    // open a transaction to ensure that the categories are synced only if the product is created
    return \DB::transaction(function () use ($data) {
      $categories = $data['categories'];

      unset($data['categories']);

      /** @var Product $model */
      $model = Product::query()->create($data);

      // Sync categories using the CategoryRepository instead of using the sync method directly
      $this->categoryRepository->syncProductCategories($model, array_column($categories, 'id'));
      // before i used this: $model->categories()->sync(array_column($categories, 'id'));

      return $model;
    });
  }

  /**
   * @param int|Product $model
   * @param array<mixed> $data
   * @return Product
   **/
  public function update(Product|int $model, array $data): Product
  {
    // open a transaction to ensure that the categories are synced only if the product is updated
    return \DB::transaction(function () use ($data, $model) {

      if (!($model instanceof Model)) {
        $model = $this->find($model);
      }

      // Sync categories using the CategoryRepository instead of using the sync method directly
      $this->categoryRepository->syncProductCategories($model, array_column($data['categories'], 'id'));

      unset($data['categories']);

      $model->update($data);

      return $model;
    });
  }

  /**
   * @param int|Product $model
   * @return bool
   **/
  public function delete(Product|int $model): bool
  {
    if (!($model instanceof Model)) {
      $model = $this->find($model);
    }

    $model->categories()->sync([]);

    return $model->delete();
  }

  private function validateSortField($sort)
  {
    return in_array($sort, self::ALLOWED_SORT_FIELDS) ? $sort : null;
  }

  /*
   * @return string
   * @param string $direction
   * */
  private function validateSortDirection(string $direction): string
  {
    return $direction === 'desc' ? 'desc' : 'asc';
  }

  private function sanitizeSearchField($search)
  {
    return $search;
  }

  /*
   * @return array<mixed>
    * @param array<mixed> $filters
   * */
  private function sanitizeFilters($filters): array
  {
    return array_intersect_key($filters, array_flip(self::ALLOWED_FILTER_FIELDS));
  }
}
