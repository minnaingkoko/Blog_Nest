<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Create New Post') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <form action="{{ route('author.posts.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <!-- Title -->
                        <div class="mb-6">
                            <label class="block font-medium text-gray-700 mb-2">Title*</label>
                            <input type="text" name="title" value="{{ old('title') }}"
                                class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:border-blue-500"
                                required>
                            @error('title')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Slug -->
                        <div class="mb-6">
                            <label class="block font-medium text-gray-700 mb-2">Slug*</label>
                            <input type="text" name="slug" value="{{ old('slug') }}"
                                class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:border-blue-500"
                                required>
                            @error('slug')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Content -->
                        <div class="mb-6">
                            <label class="block font-medium text-gray-700 mb-2">Content*</label>
                            <textarea name="content" rows="8"
                                class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:border-blue-500" required>{{ old('content') }}</textarea>
                            @error('content')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Featured Image -->
                        <div class="mb-6">
                            <label class="block font-medium text-gray-700 mb-2">Featured Image</label>
                            <input type="file" name="featured_image" accept="image/*"
                                class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:border-blue-500">
                            @error('featured_image')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            <p class="mt-1 text-sm text-gray-500">Recommended size: 1200x630 pixels</p>
                        </div>

                        <!-- Status Dropdown -->
                        <div class="mb-6">
                            <label class="block font-medium text-gray-700 mb-2">Status*</label>
                            <select name="status"
                                class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:border-blue-500"
                                required>
                                <option value="draft" {{ old('status') === 'draft' ? 'selected' : '' }}>Draft</option>
                                <option value="pending" {{ old('status') === 'pending' ? 'selected' : '' }}>Submit for
                                    Review</option>
                            </select>
                            @error('status')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Category -->
                        <div class="mb-6">
                            <label class="block font-medium text-gray-700 mb-2">Category*</label>
                            <select name="category_id"
                                class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:border-blue-500"
                                required>
                                <option value="">Select a category</option>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}"
                                        {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('category_id')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Tags -->
                        <div class="mb-6">
                            <label class="block font-medium text-gray-700 mb-2">Tags</label>
                            <div class="flex flex-wrap gap-3">
                                @foreach ($tags as $tag)
                                    <div class="flex items-center">
                                        <input type="checkbox" name="tags[]" id="tag-{{ $tag->id }}"
                                            value="{{ $tag->id }}"
                                            {{ in_array($tag->id, old('tags', [])) ? 'checked' : '' }}
                                            class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                        <label for="tag-{{ $tag->id }}"
                                            class="ml-2 text-sm text-gray-700">{{ $tag->name }}</label>
                                    </div>
                                @endforeach
                            </div>
                            @error('tags')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="flex justify-between items-center">
                            <p class="text-sm text-gray-500">
                                Choose "Draft" to save or "Submit for Review" to send for approval.
                            </p>
                            <button type="submit"
                                class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600 transition">
                                @if (old('status') === 'draft')
                                    Save as Draft
                                @else
                                    Submit for Review
                                @endif
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

@push('scripts')
    <script>
        // Auto-generate slug from title
        document.querySelector('input[name="title"]').addEventListener('input', function() {
            const title = this.value;
            const slug = title.toLowerCase()
                .replace(/[^\w\s]/g, '') // Remove special chars
                .replace(/\s+/g, '-') // Replace spaces with dashes
                .substring(0, 50); // Limit length
            document.querySelector('input[name="slug"]').value = slug;
        });

        // Initialize CKEditor if you're using it
        @if (config('app.using_ckeditor'))
            ClassicEditor
                .create(document.querySelector('textarea[name="content"]'), {
                    toolbar: ['heading', '|', 'bold', 'italic', 'link', 'bulletedList', 'numberedList', 'blockQuote']
                })
                .catch(error => {
                    console.error(error);
                });
        @endif
    </script>
@endpush
