<?php

namespace App\Http\Controllers;

use App\Http\Requests\CategoryRequest;
use App\Http\Resources\CategoryResource;
use App\Models\Category;
use App\Repositories\CategoryRepository;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
  protected CategoryRepository $categoryRepository;

  public function __construct(CategoryRepository $categoryRepository)
  {
    $this->categoryRepository = $categoryRepository;
  }

  public function index(Request $request)
  {
    $data = $this->categoryRepository->all();

    return CategoryResource::collection($data);
  }

  /**
   * Store a newly created resource in storage.
   */
  public function store(CategoryRequest $request)
  {
    $data = $request->validated();

    $category = $this->categoryRepository->create($data);

    return CategoryResource::make($category);
  }

  /**
   * Display the specified resource.
   */
  public function show(int $id)
  {
    $category = $this->categoryRepository->find($id);

    return CategoryResource::make($category);
  }


  /**
   * Update the specified resource in storage.
   */
  public function update(CategoryRequest $request, Category $category)
  {
    $data = $request->validated();

    $category = $this->categoryRepository->update($category, $data);

    return CategoryResource::make($category);
  }

  /**
   * Remove the specified resource from database.
   */
  public function destroy(Category $category)
  {
    $this->categoryRepository->delete($category);

    return response()->json([
      'success' => true,
      'message' => 'Category deleted.',
    ]);
  }
}
