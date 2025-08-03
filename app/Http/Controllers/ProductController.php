<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    function index() {
        $products = Product::all();
        $categories = Category::all();

        return view('panel.products.products', compact('products', 'categories'));
    }
    function viewer() {
        $products = Product::all();
        $categories = Category::all();

        return view('index', compact('products', 'categories'));
    }

    function store (Request $request){

        $validated = $request -> validate([
            'title'=>'required|unique:products,title|max:25',
            'price'=>'required|min:1|max:255',
            'description'=>'required|min:5|max:255',
            'category_id' => 'required|exists:categories,id',
            'image'=>'nullable|mimes:jpg,bmp,png'
        ]);

        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('products', 'public');
        } else {
            return back()->with('error', 'Image is required.');
        }

        Product::create([
            'title'=> $validated['title'] ,
            'price'=> $validated['price'] ,
            'description'=> $validated['description'] ,
            'category_id' => $validated['category_id'],
            'image' =>  $imagePath,
        ]);
        return redirect('/products');

    }

    function edit(product $product) {
        $categories = Category::all();
        return view('panel.products.edit',  compact('product', 'categories'));
    }

    function update(Request $request, product $product){

        $validated = $request->validate([
            'title' => 'required|max:25|unique:products,title,' . $product->id,
            'price' => 'required|min:1|max:255',
            'description' => 'required|min:5|max:255',
            'category_id' => 'required|exists:categories,id',
            'image' => 'nullable|mimes:jpg,bmp,png',
        ]);

        $imagePath = $product->image;

        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('products', 'public');
        }

        $product->update([
            'title' => $validated['title'],
            'price' => $validated['price'],
            'description' => $validated['description'],
            'category_id' => $validated['category_id'],
            'image' => $imagePath,
        ]);

        return redirect('/products');

    }

    function delete(Product $product) {
        $product->delete();
        return redirect('/products');
    }

    function search(Request $request)
    {
        $search = $request->input('query');
        $products = Product::search($search)->get();
        $categories = Category::all();

        return view('panel.products.products', compact('products', 'categories'));
    }



}
