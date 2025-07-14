<x-app-layout>
    <!-- Hero Section -->
    <div class="bg-gradient-to-r from-blue-500 to-purple-600 py-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h1 class="text-4xl md:text-5xl font-bold text-white mb-4">
                Welcome to Our Blog
            </h1>
            <p class="text-xl text-gray-100">
                Explore the latest posts and share your thoughts.
            </p>
        </div>
    </div>

    <!-- Posts Section -->
    <div class="py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
                <!-- Main Content -->
                <div class="lg:col-span-3">
                    <h2 class="text-3xl font-bold text-gray-900 mb-8">Latest Posts</h2>

                    @if ($posts->isEmpty())
                        <div class="text-center py-10">
                            <p class="text-gray-600 text-lg">No posts available yet. Check back later!</p>
                        </div>
                    @else
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                            @foreach ($posts as $post)
                                <!-- Post Card -->
                                <div class="bg-white rounded-lg shadow-lg overflow-hidden transform transition-all duration-300 hover:-translate-y-2 hover:shadow-xl">
                                    <a href="{{ route('posts.show', $post->slug) }}">
                                        <!-- Post Image (Optional) -->
                                        <div class="h-48 bg-gray-200 flex items-center justify-center">
                                            @if ($post->featured_image)
                                                <img src="{{ Storage::url($post->featured_image) }}" alt="{{ $post->title }}" class="h-full w-full object-cover">
                                            @else
                                                <span class="text-gray-500">No Image</span>
                                            @endif
                                        </div>

                                        <!-- Post Content -->
                                        <div class="p-6">
                                            <h3 class="text-xl font-semibold text-gray-900 mb-3 hover:text-blue-600 transition-colors duration-200">
                                                {{ $post->title }}
                                            </h3>
                                            <p class="text-gray-600 mb-4">
                                                {{ Str::limit($post->content, 120) }}
                                            </p>
                                            <div class="flex items-center justify-between text-sm text-gray-500">
                                                <span>{{ $post->created_at->format('M d, Y') }}</span>
                                                <span>{{ $post->comments_count }} Comments</span>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                            @endforeach
                        </div>

                        <!-- Pagination -->
                        <div class="mt-12">
                            {{ $posts->links() }}
                        </div>
                    @endif
                </div>

                <!-- Sidebar -->
                <div class="lg:col-span-1">
                    <!-- Category Filter -->
                    <div class="bg-white rounded-lg shadow-lg p-6 mb-8">
                        <h3 class="text-xl font-semibold text-gray-900 mb-4">Categories</h3>
                        <ul class="space-y-2">
                            @foreach ($categories as $category)
                                <li>
                                    <a href="{{ route('home', ['category' => $category->slug]) }}" class="text-gray-600 hover:text-blue-600 transition-colors duration-200">
                                        {{ $category->name }}
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </div>

                    <!-- Tag Filter -->
                    <div class="bg-white rounded-lg shadow-lg p-6">
                        <h3 class="text-xl font-semibold text-gray-900 mb-4">Tags</h3>
                        <ul class="flex flex-wrap gap-2">
                            @foreach ($tags as $tag)
                                <li>
                                    <a href="{{ route('home', ['tag' => $tag->slug]) }}" class="bg-gray-100 text-gray-600 px-3 py-1 rounded-full hover:bg-blue-100 hover:text-blue-600 transition-colors duration-200">
                                        {{ $tag->name }}
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>