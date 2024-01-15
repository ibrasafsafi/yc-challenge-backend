<?php

namespace App\Models;

use Database\Factories\CategoryFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Category extends Model
{
  use HasFactory;

  protected $guarded = [];

  protected static function newFactory(): CategoryFactory
  {
    return CategoryFactory::new();
  }

  public function parentCategory(): BelongsTo
  {
    return $this->belongsTo(Category::class, 'parent_category_id');
  }
}
