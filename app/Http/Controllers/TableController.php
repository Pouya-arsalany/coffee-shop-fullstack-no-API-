<?php

namespace App\Http\Controllers;

use App\Models\Table;
use Illuminate\Http\Request;

class TableController extends Controller
{

    public function index()
    {
        $tables = Table::all();
        return view('panel.tables.tables', compact('tables'));
    }



    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|unique:tables,title',
            'seats' => 'required|integer|min:1',
        ]);

        Table::create([
            'title' => $validated['title'],
            'seats' => $validated['seats'],
            'is_available' => true,
        ]);

        return redirect()->route('tables.index')->with('success', 'Table created successfully.');
    }

    public function destroy(Table $table)
    {
        $table->delete();
        return redirect()->route('tables.index')->with('success', 'Table deleted successfully.');
    }
}

