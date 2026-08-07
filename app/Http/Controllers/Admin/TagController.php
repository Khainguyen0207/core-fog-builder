<?php

namespace App\Http\Controllers\Admin;

use App\Admin\Forms\TagForm;
use App\Admin\Tables\TagTable;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\TagRequest;
use App\Models\Tag;
use Illuminate\Support\Str;

class TagController extends Controller
{
    public function index(TagTable $table)
    {
        return $table->renderTable();
    }

    public function create()
    {
        return TagForm::make()->renderForm();
    }

    public function store(TagRequest $request)
    {
        $data = $request->validated();
        if (empty($data['slug'])) {
            $data['slug'] = Str::slug($data['name']);
        }
        Tag::create($data);

        return redirect()->route('admin.tags.index')->with('success', 'Tag created successfully.');
    }

    public function show(Tag $tag)
    {
        return TagForm::make()->createWithModel($tag)->renderForm();
    }

    public function edit(Tag $tag)
    {
        return TagForm::make()->createWithModel($tag)->renderForm();
    }

    public function update(TagRequest $request, Tag $tag)
    {
        $data = $request->validated();
        if (empty($data['slug'])) {
            $data['slug'] = Str::slug($data['name']);
        }
        $tag->update($data);

        return redirect(request()->input('_previous_url') ?? route('admin.tags.index'))->with('success', 'Tag updated successfully.');
    }

    public function destroy(Tag $tag)
    {
        $tag->delete();

        return response()->json(['error' => false, 'message' => 'Tag deleted successfully']);
    }
}
