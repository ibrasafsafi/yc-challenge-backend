<?php

namespace App\Models;

use Database\Factories\ProductFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Product extends Model
{
  use HasFactory;

  /**
   * products table columns and that can be filled
   * @var array<int, string>
   */
  protected $fillable = ['name', 'description', 'price', 'image', 'category_id'];

  // last time i used $guarded = []; and it do the same thing but in an opposite way

  /*
   * @return ProductFactory
   * */
  protected static function newFactory(): ProductFactory
  {
    return ProductFactory::new();
  }

  /*
   * @return BelongsToMany
   * */
  public function categories(): BelongsToMany
  {
    return $this->belongsToMany(Category::class, CategoryProduct::class)
      ->using(CategoryProduct::class);
  }
}
