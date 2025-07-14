<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Manage Posts') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <!-- Header and Add Post Button -->
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-lg font-semibold">All Posts</h3>
                        <a href="{{ route('admin.posts.create') }}" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600 transition">
                            Add New Post
                        </a>
                    </div>

                    <!-- Success Message -->
                    @if (session('success'))
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
                            {{ session('success') }}
                        </div>
                    @endif

                    <!-- Status Filter -->
                    <form method="GET" action="{{ route('admin.posts.index') }}" class="mb-6">
                        <div class="flex items-center gap-4">
                            <label for="status" class="text-sm font-medium text-gray-700">Filter by Status:</label>
                            <div class="relative">
                                <select name="status" id="status" onchange="this.form.submit()" class="block appearance-none w-full bg-white border border-gray-300 text-gray-700 py-2 px-4 pr-8 rounded leading-tight focus:outline-none focus:border-blue-500">
                                    <option value="">All</option>
                                    <option value="published" {{ request('status') === 'published' ? 'selected' : '' }}>Published</option>
                                    <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                                    <option value="draft" {{ request('status') === 'draft' ? 'selected' : '' }}>Draft</option>
                                </select>
                            </div>
                        </div>
                    </form>

                    <!-- Posts Table -->
                    <div class="overflow-x-auto">
                        <table class="min-w-full bg-white">
                            <thead>
                                <tr>
                                    <th class="px-6 py-3 border-b-2 border-gray-300 text-left text-sm leading-4 font-semibold">Title</th>
                                    <th class="px-6 py-3 border-b-2 border-gray-300 text-left text-sm leading-4 font-semibold">Author</th>
                                    <th class="px-6 py-3 border-b-2 border-gray-300 text-left text-sm leading-4 font-semibold">Status</th>
                                    <th class="px-6 py-3 border-b-2 border-gray-300 text-left text-sm leading-4 font-semibold">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($posts as $post)
                                    <tr>
                                        <td class="px-6 py-4 border-b border-gray-300">{{ $post->title }}</td>
                                        <td class="px-6 py-4 border-b border-gray-300">{{ $post->user->name }}</td>
                                        <td class="px-6 py-4 border-b border-gray-300">
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                                {{ $post->status === 'published' ? 'bg-green-100 text-green-800' : 
                                                   ($post->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : 'bg-gray-100 text-gray-800') }}">
                                                {{ ucfirst($post->status) }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 border-b border-gray-300">
                                            <a href="{{ route('admin.posts.edit', $post) }}" class="text-blue-600 hover:text-blue-800 mr-2">Edit</a>
                                            @if($post->status === 'pending')
                                                <form action="{{ route('admin.posts.approve', $post) }}" method="POST" class="inline">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button type="submit" class="text-green-600 hover:text-green-800 mr-2">Approve</button>
                                                </form>
                                            @endif
                                            <form action="{{ route('admin.posts.destroy', $post) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this post?')">
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

                    <!-- Pagination -->
                    <div class="mt-4">
                        {{ $posts->appends(request()->query())->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>