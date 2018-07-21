<?php

namespace App\Http\Controllers;

use App\{Post, Category};
use Illuminate\Http\Request;

class ShowPostController extends Controller
{
	public function index(Category $category = null)
	{
        $posts = Post::orderBy('created_at', 'DESC')
            ->category($category)
            ->paginate();

        $categoryItems = $this->getCategoryItems();

		return view('posts.index', compact('posts', 'category', 'categoryItems'));
	}

    public function show(Post $post, $slug)
    {
    	if ($post->slug != $slug) {
    		return redirect($post->url, 301);
    	}

		return view('posts.show', compact('post'));
	}

    public function getCategoryItems()
    {
        return Category::orderBy('name')->get()->map(function ($category) {
            return [
                'title' => $category->name,
                'full_url' => route('posts.index', $category)
            ];
        })->toArray();
    }
}
