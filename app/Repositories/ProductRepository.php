<?php

namespace App\Repositories;

use App\Models\Product;
use Illuminate\Database\Eloquent\Model;

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

  public function all($sort = null, $direction = 'asc', $search = null, $filters = [], $perPage = 30, $defaultIncludes = true)
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

  public function find($id)
  {
    return Product::with(self::DEFAULT_INCLUDES)->find($id);
  }

  public function create($data)
  {
    return Product::query()->create($data);
  }

  public function update($model, $data)
  {
    if (!($model instanceof Model)) {
      $model = $this->find($model);
    }

    $model->update($data);

    return $model;
  }

  public function delete($model)
  {
    if (!($model instanceof Model)) {
      $model = $this->find($model);
    }

    return $model->delete();
  }

  private function validateSortField($sort)
  {
    return in_array($sort, self::ALLOWED_SORT_FIELDS) ? $sort : null;
  }

  private function validateSortDirection($direction)
  {
    return $direction === 'desc' ? 'desc' : 'asc';
  }

  private function sanitizeSearchField($search)
  {
    return $search;
  }

  private function sanitizeFilters($filters)
  {
    return array_intersect_key($filters, array_flip(self::ALLOWED_FILTER_FIELDS));
  }
}
