<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductRequest;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use App\Repositories\ProductRepository;
use Illuminate\Http\Request;

class ProductController extends Controller
{
  protected ProductRepository $productRepository;

  public function __construct(ProductRepository $productRepository)
  {
    $this->productRepository = $productRepository;
  }

  public function index(Request $request)
  {
    $data = $this->productRepository->all(
      $request->input('sort'),
      $request->input('direction', 'asc'),
      $request->input('search'),
      $request->input('filters', []),
      $request->integer('per_page', 30)
    );

    return ProductResource::collection($data);
  }

  /**
   * Store a newly created resource in storage.
   */
  public function store(ProductRequest $request)
  {
    $data = $request->validated();

    $product = $this->productRepository->create($data);

    return ProductResource::make($product);
  }

  /**
   * Display the specified resource.
   */
  public function show(int $id)
  {
    $product = $this->productRepository->find($id);

    return ProductResource::make($product);
  }


  /**
   * Update the specified resource in storage.
   */
  public function update(ProductRequest $request, Product $product)
  {
    $data = $request->validated();

    $product = $this->productRepository->update($product, $data);

    return ProductResource::make($product);
  }

  /**
   * Remove the specified resource from database.
   */
  public function destroy(Product $product)
  {
    $this->productRepository->delete($product);

    return response()->json([
      'success' => true,
      'message' => 'Product deleted.',
    ]);
  }
}
