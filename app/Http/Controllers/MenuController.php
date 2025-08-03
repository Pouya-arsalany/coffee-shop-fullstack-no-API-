<?php
namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class MenuController extends Controller
{

    public function index()
    {
        $categories = Category::all();
        $products = Product::all();

        return view('menu', compact('categories', 'products'));
    }

    public function filterByCategory($id)
    {
        $categories = Category::all();
        $products = Product::where('category_id', $id)->get();

        return view('menu', compact('categories', 'products'));
    }
}
