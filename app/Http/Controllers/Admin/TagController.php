<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Tag;
use Illuminate\Http\Request;

class TagController extends Controller
{
    // Display a listing of the tags
    public function index()
    {
        $tags = Tag::latest()->paginate(10);
        return view('admin.tags.index', compact('tags'));
    }

    // Show the form for creating a new tag
    public function create()
    {
        return view('admin.tags.create');
    }

    // Store a newly created tag in the database
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:tags',
        ]);

        Tag::create($request->only('name', 'slug'));

        return redirect()->route('admin.tags.index')
            ->with('success', 'Tag created successfully!');
    }

    // Show the form for editing the specified tag
    public function edit(Tag $tag)
    {
        return view('admin.tags.edit', compact('tag'));
    }

    // Update the specified tag in the database
    public function update(Request $request, Tag $tag)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:tags,slug,' . $tag->id,
        ]);

        $tag->update($request->only('name', 'slug'));

        return redirect()->route('admin.tags.index')
            ->with('success', 'Tag updated successfully!');
    }

    // Remove the specified tag from the database
    public function destroy(Tag $tag)
    {
        $tag->delete();

        return redirect()->route('admin.tags.index')
            ->with('success', 'Tag deleted successfully!');
    }
}
