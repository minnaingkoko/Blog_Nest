<x-app-layout>
<div class="container mx-auto px-4 py-8">
    <div class="bg-white shadow rounded-lg p-6">
        <!-- Post Header -->
        <div class="flex items-center justify-between mb-6">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">{{ $post->title }}</h1>
                <p class="text-sm text-gray-500 mt-2">
                    Posted by {{ $post->user->name }} on {{ $post->created_at->format('M d, Y') }}
                </p>
            </div>
            <div class="flex items-center space-x-4">
                <div class="flex items-center">
                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                        {{ $post->status === 'published' ? 'bg-green-100 text-green-800' : 
                           ($post->status === 'draft' ? 'bg-gray-100 text-gray-800' : 'bg-yellow-100 text-yellow-800') }}">
                        {{ ucfirst($post->status) }}
                    </span>
                </div>
            </div>
        </div>

        <!-- Featured Image -->
        @if($post->featured_image)
        <div class="mb-6">
            <img src="{{ Storage::url($post->featured_image) }}" alt="{{ $post->title }}" class="w-full h-64 object-cover rounded-lg">
        </div>
        @endif

        <!-- Post Content -->
        <div class="prose max-w-none mb-8">
            {!! $post->content !!}
        </div>

        <!-- Post Tags -->
        @if($post->tags->count() > 0)
        <div class="mb-8">
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

        <!-- Comments Section -->
        <div class="mt-8 pt-8 border-t border-gray-200">
            <h2 class="text-2xl font-bold text-gray-900 mb-4">Comments ({{ $post->comments->count() }})</h2>

            @if($canComment)
            <div class="mb-6">
                <form action="{{ route('comments.store', $post) }}" method="POST">
                    @csrf
                    <textarea name="content" rows="3" placeholder="Add a comment..." 
                        class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"></textarea>
                    <button type="submit" class="mt-2 bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition duration-200">
                        Submit Comment
                    </button>
                </form>
            </div>
            @else
            <p class="text-sm text-gray-500 mb-6">
                Please <a href="{{ route('login') }}" class="text-blue-600 hover:text-blue-800">log in</a> to comment.
            </p>
            @endif

            <!-- Display Comments -->
            @foreach($comments as $comment)
            <div class="mb-4">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-2">
                        <div class="flex-shrink-0 h-8 w-8 rounded-full bg-gray-300 flex items-center justify-center">
                            <span class="text-gray-600">{{ substr($comment->user->name, 0, 1) }}</span>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-900">{{ $comment->user->name }}</p>
                            <p class="text-sm text-gray-500">{{ $comment->created_at->format('M d, Y') }}</p>
                        </div>
                    </div>
                </div>
                <div class="ml-10 mt-2 text-sm text-gray-700">
                    {{ $comment->content }}
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>
</x-app-layout>