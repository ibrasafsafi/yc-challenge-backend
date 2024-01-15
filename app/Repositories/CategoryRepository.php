<?php

namespace App\Repositories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Model;

class CategoryRepository
{

  public function all()
  {
    return Category::all();
  }

  public function find($id)
  {
    return Category::find($id);
  }

  public function create($data)
  {
    return Category::query()->create($data);
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
}
