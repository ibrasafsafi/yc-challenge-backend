<?php

namespace App\Models;

use Database\Factories\ProductFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Product extends Model
{
  use HasFactory;

  protected $guarded = [];

  protected static function newFactory(): ProductFactory
  {
    return ProductFactory::new();
  }

  public function categories(): BelongsToMany
  {
    return $this->belongsToMany(Category::class, CategoryProduct::class)
      ->using(CategoryProduct::class);
  }
}
