<?php

namespace Modules\Catalog\Http\Admin\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Modules\Catalog\Admin\Forms\CategoryForm;
use Modules\Catalog\Admin\Tables\CategoryTable;
use Modules\Catalog\Http\Requests\CategoryRequest;

class CategoryController extends Controller
{
    public function index(CategoryTable $table)
    {
        return $table->renderTable();
    }

    public function create()
    {
        return CategoryForm::make()->renderForm();
    }

    public function store(CategoryRequest $request)
    {
        Category::create($request->validated());

        return redirect()->route('admin.categories.index')
            ->with('success', 'Category created successfully.');
    }

    public function show(Category $category)
    {
        return CategoryForm::make()->createWithModel($category)->renderForm();
    }

    public function edit(Category $category)
    {
        return CategoryForm::make()->createWithModel($category)->renderForm();
    }

    public function update(CategoryRequest $request, Category $category)
    {
        $category->update($request->validated());

        return redirect(request()->input('_previous_url') ?? route('admin.categories.index'))->with('success', 'Category updated successfully.');
    }

    public function destroy(Category $category)
    {
        $category->delete();

        return response()->json([
            'error' => false,
            'data' => null,
            'message' => 'Category deleted successfully',
        ]);
    }
}
