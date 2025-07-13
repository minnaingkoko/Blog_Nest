<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <!-- Welcome Message -->
                    <div class="mb-6">
                        <h3 class="text-lg font-semibold">Welcome, {{ Auth::user()->name }}!</h3>
                        <p class="text-sm text-gray-600">Here's an overview of your account.</p>
                    </div>

                    <!-- Quick Links -->
                    <div class="mb-8">
                        <h4 class="text-md font-semibold mb-4">Quick Links</h4>
                        <div class="flex space-x-4">
                            @if(auth()->user()->isAdmin())
                                <a href="{{ route('admin.posts.index') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition duration-200">
                                    Admin Panel
                                </a>
                            @endif
                            @if(auth()->user()->isAuthor())
                                <a href="{{ route('author.posts.index') }}" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg transition duration-200">
                                    Author Panel
                                </a>
                            @endif
                        </div>
                    </div>

                    <!-- Recent Activity -->
                    <div>
                        <h4 class="text-md font-semibold mb-4">Recent Activity</h4>
                        <div class="space-y-4">
                            @if(auth()->user()->isAuthor())
                                <!-- Display recent posts for authors -->
                                @forelse(Auth::user()->posts->take(3) as $post)
                                    <div class="p-4 border border-gray-200 rounded-lg">
                                        <p class="text-sm text-gray-600">{{ $post->created_at->format('M d, Y') }}</p>
                                        <p class="text-md font-medium text-gray-900">{{ $post->title }}</p>
                                    </div>
                                @empty
                                    <p class="text-sm text-gray-600">No recent posts found.</p>
                                @endforelse
                            @endif
                            @if(auth()->user()->isAdmin())
                                <!-- Display recent activity for admins -->
                                <p class="text-sm text-gray-600">No recent activity found.</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>