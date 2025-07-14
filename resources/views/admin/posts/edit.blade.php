<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Post') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <form action="{{ route('admin.posts.update', $post) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="mb-6">
                            <label class="block font-medium text-gray-700 mb-2">Title</label>
                            <input type="text" name="title" value="{{ $post->title }}"
                                   class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:border-blue-500" required>
                        </div>

                        <div class="mb-6">
                            <label class="block font-medium text-gray-700 mb-2">Slug</label>
                            <input type="text" name="slug" value="{{ $post->slug }}"
                                   class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:border-blue-500" required>
                        </div>

                        <div class="mb-6">
                            <label class="block font-medium text-gray-700 mb-2">Content</label>
                            <textarea name="content" rows="8"
                                      class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:border-blue-500" required>{{ $post->content }}</textarea>
                        </div>

                        <div class="mb-6">
                            <label class="block font-medium text-gray-700 mb-2">Featured Image</label>
                            @if($post->featured_image)
                                <div class="mb-2">
                                    <img src="{{ asset('storage/' . $post->featured_image) }}" alt="Featured Image" class="h-32">
                                </div>
                                <div class="flex items-center">
                                    <input type="checkbox" name="remove_image" id="remove_image" class="mr-2">
                                    <label for="remove_image">Remove current image</label>
                                </div>
                            @endif
                            <input type="file" name="featured_image" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:border-blue-500 mt-2">
                        </div>

                        <div class="mb-6">
                            <label class="block font-medium text-gray-700 mb-2">Status</label>
                            <select name="status" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:border-blue-500" required>
                                <option value="draft" {{ $post->status === 'draft' ? 'selected' : '' }}>Draft</option>
                                <option value="published" {{ $post->status === 'published' ? 'selected' : '' }}>Published</option>
                            </select>
                        </div>

                        <div class="mb-6">
                            <label class="block font-medium text-gray-700 mb-2">Category</label>
                            <select name="category_id" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:border-blue-500" required>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ $post->category_id === $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-6">
                            <label class="block font-medium text-gray-700 mb-2">Tags</label>
                            <select name="tags[]" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:border-blue-500" multiple>
                                @foreach($tags as $tag)
                                    <option value="{{ $tag->id }}" {{ $post->tags->contains($tag->id) ? 'selected' : '' }}>{{ $tag->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="flex justify-end">
                            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600 transition">
                                Update Post
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>