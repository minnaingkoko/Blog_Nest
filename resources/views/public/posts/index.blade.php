<x-guest-layout>

@section('content')
    <div class="py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h1 class="text-3xl font-bold mb-8">Latest Posts</h1>
            @foreach ($posts as $post)
                <div class="bg-white shadow-sm rounded-lg p-6 mb-6">
                    <h2 class="text-2xl font-semibold">{{ $post->title }}</h2>
                    <p class="mt-2 text-gray-600">{{ Str::limit($post->excerpt, 200) }}</p>
                    <a href="{{ route('posts.show', $post->slug) }}" class="inline-block mt-4 px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600">Read More</a>
                </div>
            @endforeach
            {{ $posts->links() }} <!-- Pagination -->
        </div>
    </div>
</x-guest-layout>