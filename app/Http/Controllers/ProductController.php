<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductRequest;
use App\Http\Requests\UploadImageRequest;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use App\Repositories\ProductRepository;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class ProductController extends Controller
{
  protected ProductRepository $productRepository;

  public function __construct(ProductRepository $productRepository)
  {
    $this->productRepository = $productRepository;
  }

  /*
   * @param Request $request
   * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
   * */
  public function index(Request $request): AnonymousResourceCollection
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
   * @param ProductRequest $request
   * @return ProductResource
   */
  public function store(ProductRequest $request): ProductResource
  {
    $data = $request->validated();

    $product = $this->productRepository->create($data);

    return ProductResource::make($product);
  }

  /**
   * Display the specified resource.
   * @param int $id
   * @return ProductResource
   */
  public function show(int $id): ProductResource
  {
    $product = $this->productRepository->find($id);

    return ProductResource::make($product);
  }


  /**
   * Update the specified resource in storage.
   * @param ProductRequest $request
   * @param Product $product
   * @return ProductResource
   */
  public function update(ProductRequest $request, Product $product): ProductResource
  {
    $data = $request->validated();

    $product = $this->productRepository->update($product, $data);

    return ProductResource::make($product);
  }

  /**
   * Remove the specified resource from database.
   * @param Product $product
   * @return \Illuminate\Http\JsonResponse
   */
  public function destroy(Product $product): \Illuminate\Http\JsonResponse
  {
    $this->productRepository->delete($product);

    return response()->json([
      'success' => true,
      'message' => 'Product deleted.',
    ]);
  }

  /**
   * Upload image
   * @param UploadImageRequest $request
   * @return string
   */
  public function upload(UploadImageRequest $request): string
  {
    $image = $request->file('image');

    $imageName = time() . $image->getClientOriginalName();

    $image->storeAs('products', $imageName, 'public');

    return $imageName;

  }

}
