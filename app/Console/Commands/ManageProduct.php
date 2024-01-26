<?php

namespace App\Console\Commands;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Console\Command;
use Illuminate\Http\File;
use Illuminate\Support\Facades\Storage;

class ManageProduct extends Command
{
  /**
   * The name and signature of the console command.
   *
   * @var string
   */
  protected $signature = 'product:manage {--create : Create a new product}
                                         {--update : Update an existing product}
                                         {--delete : Delete a product}';


  /**
   * The console command description.
   *
   * @var string
   */
  protected $description = 'Manage products (CRUD) with categories (many-to-many) using Command Line Interface (CLI)';

  /**
   * Execute the console command.
   */
  public function handle()
  {
    if ($this->option('create')) {
      $this->createProduct();
    } elseif ($this->option('update')) {
      $this->updateProduct();
    } elseif ($this->option('delete')) {
      $this->deleteProduct();
    } else {
      $this->info('Please specify an operation: --create, --update, or --delete');
    }
  }

  /*
   * @return void
   * */
  private function createProduct(): void
  {
    $product = new Product();
    $product->name = $this->ask('Enter product name:');
    $product->description = $this->ask('Enter product description:');
    $product->price = $this->ask('Enter product price:');

    // Handle image upload
    $imagePath = $this->ask('Enter image path:(Exp: D:\images\image.jpg)');

    if (file_exists($imagePath)) {
      $imagePath = Storage::putFile('public/products', $imagePath);
      $imagePath = explode('/', $imagePath);
      $product->image = $imagePath[2];

    } else {
      $this->error('File does not exist. Please provide a valid file path.');
      return;
    }

    // Save the product
    $product->save();

    // Associate with categories
    $categoryNames = $this->ask('Enter category names (comma-separated):');
    $categoryNames = explode(',', $categoryNames);

    foreach ($categoryNames as $categoryName) {
      $category = Category::firstOrCreate(['name' => trim($categoryName)]);
      $product->categories()->attach($category->id);
    }

    $this->info('Product created successfully.');
  }

  /*
   * @return void
   * */
  private function updateProduct(): void
  {
    $productId = $this->ask('Enter product ID:');
    $product = Product::find($productId);

    if (!$product) {
      $this->error('Product not found.');
      return;
    }

    $this->info('Leave a field blank to skip updating it.');

    // Update product name
    $productName = $this->ask('Enter product name (current: ' . $product->name . '):');
    if (!empty($productName)) {
      $product->name = $productName;
    }

    // Update product description
    $productDescription = $this->ask('Enter product description (current: ' . $product->description . '):');
    if (!empty($productDescription)) {
      $product->description = $productDescription;
    }

    // Update product price
    $productPrice = $this->ask('Enter product price (current: ' . $product->price . '):');
    if (!empty($productPrice)) {
      $product->price = $productPrice;
    }

    // Handle image upload
    $imagePath = $this->ask('Enter image path (Exp: D:\images\image.jpg):');
    if (!empty($imagePath) && file_exists($imagePath)) {
      $imagePath = Storage::putFile('public/products', $imagePath);
      $imagePath = explode('/', $imagePath);
      $product->image = $imagePath[2];
    } elseif (!empty($imagePath)) {
      $this->error('File does not exist. Please provide a valid file path.');
      return;
    }

    // Save the product
    $product->save();

    // Associate with categories
    $categoryNames = $this->ask('Enter category names (comma-separated):');
    $categoryNames = explode(',', $categoryNames);

    foreach ($categoryNames as $categoryName) {

      $category = Category::firstOrCreate(['name' => trim($categoryName)]);
      $product->categories()->attach($category->id);
    }

    $this->info('Product updated successfully.');
  }


  /*
   * @return void
   * */
  private function deleteProduct(): void
  {
    $productId = $this->ask('Enter product ID:');
    $product = Product::find($productId);

    if (!$product) {
      $this->error('Product not found.');
      return;
    }

    $product->categories()->detach();
    $product->delete();

    $this->info('Product deleted successfully.');
  }

}
