<?php

namespace App\Http\Controllers;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CategoryController extends Controller
{
  
 public function index()
{
    $categories = Category::paginate(12); 
    return view('categories.index', compact('categories'));
}

public function welcomeCategories()
{
    $categories = Category::where('status', true)->take(12)->get(); // For welcome page carousel
     return view('welcome', compact('categories'));
}

public function create()
{
    return view('categories.create');
}

public function store(Request $request)
{
    $request->validate([
        'category_name' => 'required|string|max:255',
        'category_picture' => 'nullable|image|max:2048',
        'category_page_link' => 'nullable|url',
    ]);

    $data = $request->only(['category_name', 'category_page_link']);

    if ($request->hasFile('category_picture')) {
        $data['category_picture'] = $request->file('category_picture')->store('categories', 'public');
    }

    Category::create($data);

    return redirect()->route('categories.index')->with('success', 'Category created successfully!');
}


public function toggleStatus(Request $request, Category $category)
{
    try {
        $newStatus = !$category->status;
        $category->update(['status' => $newStatus]);
        
        $statusText = $newStatus ? 'activated' : 'deactivated';
        return back()->with('success', "Category {$statusText} successfully!");
        
    } catch (\Exception $e) {
        return back()->with('error', 'Failed to update status: ' . $e->getMessage());
    }
}


public function edit($id)
{
    $category = Category::findOrFail($id);
    return view('categories.edit', compact('category'));
}


public function update(Request $request, $id)
{
    $request->validate([
        'category_name' => 'required|string|max:255',
        'category_picture' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        'category_page_link' => 'nullable|url',
        'status' => 'required|boolean',
    ]);

    $category = Category::findOrFail($id);
    $category->category_name = $request->category_name;
    $category->category_page_link = $request->category_page_link;
    $category->status = $request->status;

    if ($request->hasFile('category_picture')) {
        $category->category_picture = $request->file('category_picture')->store('category_pictures', 'public');
    }

    $category->save();

    return redirect()->route('categories.index')->with('success', 'Category updated successfully.');
}
}
