<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CategoryController extends Controller
{
    function index() {
        $categories = Category::all();
        return view('panel.categories.categories', ['categories' => $categories]);
    }

    function store (Request $request){

        $validated = $request -> validate([
            'title'=>'required|unique:categories,title|max:25',
            'image'=>'required|mimes:jpg,bmp,png'
        ]);

        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('categories', 'public');
        } else {
            return back()->with('error', 'Image is required.');
        }

        Category::create([
            'title'=> $validated['title'] ,
            'image' =>  $imagePath
        ]);
        return redirect('/categories');
    }

    function edit(Category $category) {
        return view('panel.categories.edit', compact('category'));
    }

    function update(Request $request, Category $category){

        $imagePath = $request->image
            ? $request->file('image')->store('categories', 'public')
            : $request->existing_image;

        $category->update([
            'title' => $request->title,
            'image' => $imagePath,
        ]);
        return redirect('/categories');

    }

    function delete(Category $category) {
        $category->delete();
        return redirect('/categories');
    }

    function search (request $request){

        $search = $request ->input('query');
        $titles = Category::search($search)->get();
        return view('panel.categories.categories', ['categories' => $titles]);

    }
}

