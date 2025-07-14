<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Category') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <form action="{{ route('admin.categories.update', $category) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="mb-6">
                            <label class="block font-medium text-gray-700 mb-2">Name</label>
                            <input type="text" name="name" value="{{ $category->name }}"
                                   class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:border-blue-500" required>
                        </div>

                        <div class="mb-6">
                            <label class="block font-medium text-gray-700 mb-2">Slug</label>
                            <input type="text" name="slug" value="{{ $category->slug }}"
                                   class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:border-blue-500" required>
                        </div>

                        <div class="mb-6">
                            <label class="block font-medium text-gray-700 mb-2">Description</label>
                            <textarea name="description" rows="3"
                                      class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:border-blue-500">{{ $category->description }}</textarea>
                        </div>

                        <div class="flex justify-end">
                            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600 transition">
                                Update Category
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>