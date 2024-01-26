<?php

namespace App\Http\Controllers;

use App\Http\Requests\CategoryRequest;
use App\Http\Resources\CategoryResource;
use App\Models\Category;
use App\Repositories\CategoryRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class CategoryController extends Controller
{
  protected CategoryRepository $categoryRepository;

  public function __construct(CategoryRepository $categoryRepository)
  {
    $this->categoryRepository = $categoryRepository;
  }

  /*
   * Display a listing of the resource.
   * @param Request $request
   * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
   * */
  public function index(Request $request): AnonymousResourceCollection
  {
    $data = $this->categoryRepository->all();

    return CategoryResource::collection($data);
  }

  /**
   * Store a newly created resource in storage.
   * @param CategoryRequest $request
   * @return CategoryResource
   */
  public function store(CategoryRequest $request): CategoryResource
  {
    $data = $request->validated();

    $category = $this->categoryRepository->create($data);

    return CategoryResource::make($category);
  }

  /**
   * Display the specified resource.
   * @param int $id
   * @return CategoryResource
   */
  public function show(int $id): CategoryResource
  {
    $category = $this->categoryRepository->find($id);

    return CategoryResource::make($category);
  }


  /**
   * Update the specified resource in storage.
   * @param CategoryRequest $request
   * @param Category $category
   * @return CategoryResource
   */
  public function update(CategoryRequest $request, Category $category): CategoryResource
  {
    $data = $request->validated();

    $category = $this->categoryRepository->update($category, $data);

    return CategoryResource::make($category);
  }

  /**
   * Remove the specified resource from database.
   * @param Category $category
   * @return JsonResponse
   */
  public function destroy(Category $category): JsonResponse
  {
    $this->categoryRepository->delete($category);

    return response()->json([
      'success' => true,
      'message' => 'Category deleted.',
    ]);
  }
}
