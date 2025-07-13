@extends('layouts.admin')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-8">
        <h1 class="text-3xl font-bold text-gray-900">{{ $post->title }}</h1>
        <div class="flex space-x-4">
            <a href="{{ route('admin.posts.edit', $post) }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition duration-200">
                Edit
            </a>
            <form action="{{ route('admin.posts.destroy', $post) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this post?')">
                @csrf
                @method('DELETE')
                <button type="submit" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg transition duration-200">
                    Delete
                </button>
            </form>
        </div>
    </div>

    <div class="bg-white shadow rounded-lg overflow-hidden">
        @if($post->featured_image)
        <div class="h-64 w-full bg-gray-200 flex items-center justify-center overflow-hidden">
            <img src="{{ Storage::url($post->featured_image) }}" alt="{{ $post->title }}" class="h-full w-full object-cover">
        </div>
        @endif

        <div class="p-6">
            <div class="flex items-center justify-between mb-6">
                <div class="flex items-center space-x-4">
                    <div class="flex-shrink-0 h-10 w-10 rounded-full bg-gray-300 flex items-center justify-center">
                        @if($post->user->avatar)
                            <img src="{{ Storage::url($post->user->avatar) }}" alt="{{ $post->user->name }}" class="h-full w-full rounded-full object-cover">
                        @else
                            <span class="text-gray-600">{{ substr($post->user->name, 0, 1) }}</span>
                        @endif
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-900">{{ $post->user->name }}</p>
                        <p class="text-sm text-gray-500">
                            {{ $post->published_at ? $post->published_at->format('M d, Y') : 'Not published' }}
                        </p>
                    </div>
                </div>
                <span class="px-3 py-1 rounded-full text-sm font-medium 
                    {{ $post->status === 'published' ? 'bg-green-100 text-green-800' : 
                       ($post->status === 'draft' ? 'bg-gray-100 text-gray-800' : 'bg-yellow-100 text-yellow-800') }}">
                    {{ ucfirst($post->status) }}
                </span>
            </div>

            <div class="prose max-w-none mb-6">
                <h2 class="text-xl font-semibold mb-4">Excerpt</h2>
                <p>{{ $post->excerpt }}</p>
            </div>

            <div class="prose max-w-none mb-6">
                <h2 class="text-xl font-semibold mb-4">Content</h2>
                {!! $post->content !!}
            </div>

            <div class="mb-6">
                <h2 class="text-xl font-semibold mb-4">Category</h2>
                <span class="inline-block bg-gray-100 rounded-full px-3 py-1 text-sm font-semibold text-gray-700">
                    {{ $post->category->name }}
                </span>
            </div>

            @if($post->tags->count() > 0)
            <div class="mb-6">
                <h2 class="text-xl font-semibold mb-4">Tags</h2>
                <div class="flex flex-wrap gap-2">
                    @foreach($post->tags as $tag)
                        <span class="inline-block bg-blue-100 rounded-full px-3 py-1 text-sm font-semibold text-blue-700">
                            {{ $tag->name }}
                        </span>
                    @endforeach
                </div>
            </div>
            @endif

            <div class="mt-8 pt-6 border-t border-gray-200">
                <a href="{{ route('admin.posts.index') }}" class="text-blue-600 hover:text-blue-800">
                    ← Back to all posts
                </a>
            </div>
        </div>
    </div>
</div>
@endsection