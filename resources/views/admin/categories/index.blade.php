<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Manage Categories') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-lg font-semibold">All Categories</h3>
                        <a href="{{ route('admin.categories.create') }}" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600 transition">
                            Add New Category
                        </a>
                    </div>

                    @if (session('success'))
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
                            {{ session('success') }}
                        </div>
                    @endif

                    <div class="overflow-x-auto">
                        <table class="min-w-full bg-white">
                            <thead>
                                <tr>
                                    <th class="px-6 py-3 border-b-2 border-gray-300 text-left text-sm leading-4 font-semibold">Name</th>
                                    <th class="px-6 py-3 border-b-2 border-gray-300 text-left text-sm leading-4 font-semibold">Slug</th>
                                    <th class="px-6 py-3 border-b-2 border-gray-300 text-left text-sm leading-4 font-semibold">Description</th>
                                    <th class="px-6 py-3 border-b-2 border-gray-300 text-left text-sm leading-4 font-semibold">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($categories as $category)
                                    <tr>
                                        <td class="px-6 py-4 border-b border-gray-300">{{ $category->name }}</td>
                                        <td class="px-6 py-4 border-b border-gray-300">{{ $category->slug }}</td>
                                        <td class="px-6 py-4 border-b border-gray-300">{{ $category->description }}</td>
                                        <td class="px-6 py-4 border-b border-gray-300">
                                            <a href="{{ route('admin.categories.edit', $category) }}" class="text-blue-600 hover:text-blue-800 mr-2">Edit</a>
                                            <form action="{{ route('admin.categories.destroy', $category) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this category?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-800">Delete</button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4">
                        {{ $categories->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>