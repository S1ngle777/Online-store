<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\Post;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $products = Product::inRandomOrder()->take(3)->get();
        $categories = Category::inRandomOrder()->take(4)->get();
        $posts = Post::where('is_published', true)
            ->orderBy('published_at', 'desc')
            ->take(3)
            ->get();

        return view('home', compact('products', 'categories', 'posts'));
    }
}
