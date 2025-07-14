<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Admin Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Stats Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <h3 class="text-lg font-semibold text-gray-900">Total Users</h3>
                    <p class="text-3xl font-bold">{{ $stats['userCount'] }}</p>
                    <div class="flex justify-between mt-2 text-sm">
                        <span class="text-green-600">Admins: {{ $stats['adminCount'] }}</span>
                        <span class="text-blue-600">Authors: {{ $stats['authorCount'] }}</span>
                        <span class="text-gray-600">Users: {{ $stats['regularUserCount'] }}</span>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <h3 class="text-lg font-semibold text-gray-900">Total Posts</h3>
                    <p class="text-3xl font-bold">{{ $stats['postCount'] }}</p>
                    <div class="flex justify-between mt-2 text-sm">
                        <span class="text-green-600">Published: {{ $stats['publishedPostCount'] }}</span>
                        <span class="text-gray-600">Drafts: {{ $stats['draftPostCount'] }}</span>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <h3 class="text-lg font-semibold text-gray-900">Recent Users</h3>
                    <div class="mt-2 space-y-1">
                        @foreach ($stats['recentUsers'] as $user)
                            <div class="flex items-center justify-between">
                                <span>{{ $user->name }}</span>
                                <span
                                    class="text-xs px-2 py-1 rounded-full {{ $user->role_id === 1 ? 'bg-red-100 text-red-800' : ($user->role_id === 2 ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-800') }}">
                                    {{ $user->role->name }}
                                </span>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <h3 class="text-lg font-semibold text-gray-900">Recent Posts</h3>
                    <div class="mt-2 space-y-1">
                        @foreach ($stats['recentPosts'] as $post)
                            <div class="flex items-center justify-between">
                                <span class="truncate">{{ $post->title }}</span>
                                <span
                                    class="text-xs px-2 py-1 rounded-full {{ $post->status === 'published' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                    {{ ucfirst($post->status) }}
                                </span>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-8">
                <div class="p-6 text-gray-900">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-lg font-semibold">Manage Content</h3>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        <!-- Manage Users Card -->
                        <a href="{{ route('admin.users.index') }}"
                            class="block p-4 border border-gray-200 rounded-lg hover:bg-gray-50 transition">
                            <div class="flex items-center">
                                <div class="p-3 rounded-full bg-purple-100 text-purple-600 mr-4">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                                    </svg>
                                </div>
                                <div>
                                    <h4 class="text-md font-semibold">Manage Users</h4>
                                    <p class="text-sm text-gray-600">View and manage all users</p>
                                </div>
                            </div>
                        </a>

                        <!-- Manage Posts Card -->
                        <a href="{{ route('admin.posts.index') }}"
                            class="block p-4 border border-gray-200 rounded-lg hover:bg-gray-50 transition">
                            <div class="flex items-center">
                                <div class="p-3 rounded-full bg-blue-100 text-blue-600 mr-4">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M4 6h16M4 12h16M4 18h16" />
                                    </svg>
                                </div>
                                <div>
                                    <h4 class="text-md font-semibold">Manage Posts</h4>
                                    <p class="text-sm text-gray-600">View and manage all posts</p>
                                </div>
                            </div>
                        </a>

                        <a href="{{ route('admin.categories.index') }}"
                            class="block p-4 border border-gray-200 rounded-lg hover:bg-gray-50 transition">
                            <div class="flex items-center">
                                <div class="p-3 rounded-full bg-yellow-100 text-yellow-600 mr-4">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M7 7h.01M7 3h5a2 2 0 012 2v5m-6 0h10a2 2 0 012 2v5a2 2 0 01-2 2H7a2 2 0 01-2-2V7z" />
                                    </svg>
                                </div>
                                <div>
                                    <h4 class="text-md font-semibold">Manage Categories</h4>
                                    <p class="text-sm text-gray-600">View and manage all categories</p>
                                </div>
                            </div>
                        </a>

                        <a href="{{ route('admin.tags.index') }}"
                            class="block p-4 border border-gray-200 rounded-lg hover:bg-gray-50 transition">
                            <div class="flex items-center">
                                <div class="p-3 rounded-full bg-indigo-100 text-indigo-600 mr-4">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14" />
                                    </svg>
                                </div>
                                <div>
                                    <h4 class="text-md font-semibold">Manage Tags</h4>
                                    <p class="text-sm text-gray-600">View and manage all tags</p>
                                </div>
                            </div>
                        </a>

                        <!-- General Settings Card -->
                        <a href="{{ route('admin.settings.edit') }}"
                            class="block p-4 border border-gray-200 rounded-lg hover:bg-gray-50 transition">
                            <div class="flex items-center">
                                <div class="p-3 rounded-full bg-green-100 text-green-600 mr-4">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" />
                                    </svg>
                                </div>
                                <div>
                                    <h4 class="text-md font-semibold">General Settings</h4>
                                    <p class="text-sm text-gray-600">Configure site settings</p>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
