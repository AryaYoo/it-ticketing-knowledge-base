<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index()
    {
        $search = request('search');

        $categories = \App\Models\Category::when($search, function ($query) use ($search) {
            $query->where('name', 'like', "%{$search}%");
        })
            ->paginate(10)
            ->appends(['search' => $search]);

        return view('admin.categories.index', compact('categories', 'search'));
    }

    public function create()
    {
        return view('admin.categories.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:categories',
        ]);

        \App\Models\Category::create([
            'name' => $request->name,
            'is_active' => true,
        ]);

        return redirect()->route('categories.index')->with('status', 'Category created successfully!');
    }

    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        $category = \App\Models\Category::findOrFail($id);
        return view('admin.categories.edit', compact('category'));
    }

    public function update(Request $request, $id)
    {
        $category = \App\Models\Category::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255|unique:categories,name,' . $category->id,
            'is_active' => 'boolean',
        ]);

        $category->update([
            'name' => $request->name,
            'is_active' => $request->has('is_active'), // Checkbox handling
        ]);

        return redirect()->route('categories.index')->with('status', 'Category updated successfully!');
    }

    public function destroy($id)
    {
        $category = \App\Models\Category::findOrFail($id);
        $category->delete();
        return redirect()->route('categories.index')->with('status', 'Category deleted successfully!');
    }
}
