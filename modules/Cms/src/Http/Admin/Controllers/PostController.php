<?php

namespace Modules\Cms\Http\Admin\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\PostView;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Modules\Cms\Admin\Forms\PostForm;
use Modules\Cms\Admin\Tables\PostTable;
use Modules\Cms\Http\Requests\PostRequest;

class PostController extends Controller
{
    public function index(PostTable $table)
    {
        return $table->renderTable();
    }

    public function create()
    {
        return PostForm::make()->renderForm();
    }

    public function store(PostRequest $request)
    {
        $data = $request->validated();

        $file = $request->file('image');

        $filename = Str::uuid().'.'.$file->getClientOriginalExtension();

        $path = $file->storeAs(
            'images',
            $filename,
            'public'
        );

        $data['image'] = $path;
        $data['slug'] = $this->generateSlugFromString(Arr::get($request, 'slug') ?? $data['title']);

        $post = Post::create($data);

        if (! empty($data['blog_categories'])) {
            $post->blogCategories()->sync($data['blog_categories']);
        }

        PostView::create([
            'post_id' => $post->post_id,
            'view_count' => 0,
            'like_count' => 0,
        ]);

        return redirect()->route('admin.posts.index')->with('success', 'Post created successfully.');
    }

    public function show(Post $post)
    {
        $form = PostForm::make()->createWithModel($post);

        $form->getField('blog_categories')->setValue(
            $post->blogCategories->pluck('category_id')->toArray()
        );

        return $form->renderForm();
    }

    public function edit(Post $post)
    {
        $form = PostForm::make()->createWithModel($post);

        $form->getField('blog_categories')->setValue(
            $post->blogCategories->pluck('category_id')->toArray()
        );

        return $form->renderForm();
    }

    public function update(PostRequest $request, Post $post)
    {
        $data = $request->validated();

        if (empty($data['slug'])) {
            $data['slug'] = $this->generateSlugFromString($data['title']);
        }

        if ($request->hasFile('image')) {
            $file = $request->file('image');

            $filename = Str::uuid().'.'.$file->getClientOriginalExtension();

            $path = $file->storeAs(
                'images',
                $filename,
                'public'
            );

            $oldImage = $post->getOriginal('image');

            if ($oldImage) {
                Storage::disk('public')->delete($oldImage);
            }

            $data['image'] = $path;
        }

        $post->update($data);

        if (isset($data['blog_categories'])) {
            $post->blogCategories()->sync($data['blog_categories']);
        } else {
            $post->blogCategories()->detach();
        }

        return redirect(request()->input('_previous_url') ?? route('admin.posts.index'))->with('success', 'Post updated successfully.');
    }

    public function destroy(Post $post)
    {
        $post->delete();

        return response()->json(['error' => false, 'message' => 'Post deleted successfully']);
    }

    public function generateSlugFromString(string $text): string
    {
        $baseSlug = Str::slug($text);

        do {
            $count = Post::query()->where('slug', 'LIKE', $baseSlug.'%')->count();

            $baseSlug = $count
                ? $baseSlug.'-'.$count
                : $baseSlug;

            $exists = Post::query()->where('slug', $baseSlug)->exists();
        } while ($exists);

        return $baseSlug;
    }
}
