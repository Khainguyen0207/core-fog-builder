<?php

namespace App\Http\Controllers\Admin;

use App\Admin\Forms\BlogCategoryForm;
use App\Admin\Tables\BlogCategoryTable;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\BlogCategoryRequest;
use App\Models\BlogCategory;
use Illuminate\Support\Str;

class BlogCategoryController extends Controller
{
    public function index(BlogCategoryTable $table)
    {
        return $table->renderTable();
    }

    public function create()
    {
        return BlogCategoryForm::make()->renderForm();
    }

    public function store(BlogCategoryRequest $request)
    {
        $data = $request->validated();
        if (empty($data['slug'])) {
            $data['slug'] = Str::slug($data['name']);
        }
        BlogCategory::create($data);

        return redirect()->route('admin.blog-categories.index')->with('success', 'Category created successfully.');
    }

    public function show(BlogCategory $blog_category)
    {
        return BlogCategoryForm::make()->createWithModel($blog_category)->renderForm();
    }

    public function edit(BlogCategory $blog_category)
    {
        return BlogCategoryForm::make()->createWithModel($blog_category)->renderForm();
    }

    public function update(BlogCategoryRequest $request, BlogCategory $blog_category)
    {
        $data = $request->validated();
        if (empty($data['slug'])) {
            $data['slug'] = Str::slug($data['name']);
        }
        $blog_category->update($data);

        return redirect(request()->input('_previous_url') ?? route('admin.blog-categories.index'))->with('success', 'Category updated successfully.');
    }

    public function destroy(BlogCategory $blog_category)
    {
        $blog_category->delete();

        return response()->json(['error' => false, 'message' => 'Category deleted successfully']);
    }
}
