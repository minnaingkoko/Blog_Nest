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
                        <span
                            class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                            {{ $post->status === 'published'
                                ? 'bg-green-100 text-green-800'
                                : ($post->status === 'draft'
                                    ? 'bg-gray-100 text-gray-800'
                                    : 'bg-yellow-100 text-yellow-800') }}">
                            {{ ucfirst($post->status) }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- Featured Image -->
            @if ($post->featured_image)
                <div class="mb-6">
                    <img src="{{ Storage::url($post->featured_image) }}" alt="{{ $post->title }}"
                        class="w-full h-64 object-cover rounded-lg">
                </div>
            @endif

            <!-- Post Content -->
            <div class="prose max-w-none mb-8">
                {!! $post->content !!}
            </div>

            <!-- Post Tags -->
            @if ($post->tags->count() > 0)
                <div class="mb-8">
                    <h2 class="text-xl font-semibold mb-4">Tags</h2>
                    <div class="flex flex-wrap gap-2">
                        @foreach ($post->tags as $tag)
                            <span
                                class="inline-block bg-blue-100 rounded-full px-3 py-1 text-sm font-semibold text-blue-700">
                                {{ $tag->name }}
                            </span>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Like Button for Post -->
            <div class="mt-4 flex items-center space-x-4">
                <button onclick="toggleLike('App\\Models\\Post', {{ $post->id }})"
                    class="flex items-center text-sm text-gray-500 hover:text-blue-600">
                    <span id="like-count-post-{{ $post->id }}">{{ $post->likes->count() }}</span>
                    <span class="ml-1">Like</span>
                </button>
            </div>

            <!-- Comments Section -->
            <div class="mt-8 pt-8 border-t border-gray-200">
                <!-- Comment Count -->
                <h2 class="text-2xl font-bold text-gray-900 mb-4">Comments ({{ $post->comments->count() }})</h2>

                <!-- Comment Form -->
                @auth
                    <div class="mb-6">
                        <form action="{{ route('comments.store', $post) }}" method="POST">
                            @csrf
                            <textarea name="content" rows="3" placeholder="Add a comment..."
                                class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"></textarea>
                            <button type="submit"
                                class="mt-2 bg-blue-600 hover:bg-blue-7\00 text-white px-4 py-2 rounded-lg transition duration-200">
                                Submit Comment
                            </button>
                        </form>
                    </div>
                @else
                    <p class="text-sm text-gray-500 mb-6">
                        Please <a href="{{ route('login') }}" class="text-blue-600 hover:text-blue-800">log in</a> to
                        comment.
                    </p>
                @endauth

                <!-- Display Approved Comments -->
                @foreach ($post->comments as $comment)
                    <div class="mb-4 p-4 border border-gray-100 rounded-lg" id="comment-{{ $comment->id }}">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <img src="{{ $comment->user->profile_photo_url ?? asset('images/default-avatar.png') }}"
                                    alt="{{ $comment->user->name }}" class="w-8 h-8 rounded-full">
                                <div class="ml-3">
                                    <p class="font-medium text-gray-900">{{ $comment->user->name }}</p>
                                    <p class="text-sm text-gray-500">{{ $comment->created_at->diffForHumans() }}</p>
                                </div>
                            </div>
                        </div>
                        <p class="mt-2 text-gray-700">{{ $comment->content }}</p>

                        <!-- Like, Show Replies, and Reply Buttons -->
                        <div class="mt-4 flex items-center space-x-4">
                            <!-- Like Button for Comment -->
                            <button onclick="toggleLike('App\\Models\\Comment', {{ $comment->id }})"
                                class="flex items-center text-sm text-gray-500 hover:text-blue-600">
                                <span id="like-count-comment-{{ $comment->id }}">{{ $comment->likes->count() }}</span>
                                <span class="ml-1">Like</span>
                            </button>

                            <!-- Show Replies Button -->
                            @if ($comment->replies->count() > 0)
                                <button onclick="toggleReplies({{ $comment->id }})"
                                    class="text-sm text-gray-500 hover:text-blue-600">
                                    Show Replies (<span
                                        id="reply-count-{{ $comment->id }}">{{ $comment->replies->count() }}</span>)
                                </button>
                            @endif

                            <!-- Reply Button -->
                            <button onclick="showReplyForm({{ $comment->id }})"
                                class="text-sm text-gray-5\00 hover:text-blue-600">
                                Reply
                            </button>
                        </div>

                        <!-- Reply Form (Hidden by Default) -->
                        <div id="reply-form-{{ $comment->id }}" class="mt-4 hidden">
                            <form action="{{ route('comments.store', $post) }}" method="POST">
                                @csrf
                                <input type="hidden" name="parent_id" value="{{ $comment->id }}">
                                <textarea name="content" rows="2" placeholder="Write a reply..."
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"></textarea>
                                <button type="submit"
                                    class="mt-2 bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition duration-200">
                                    Submit Reply
                                </button>
                            </form>
                        </div>

                        <!-- Nested Replies (Hidden by Default) -->
                        <div id="replies-{{ $comment->id }}" class="ml-8 mt-4 hidden">
                            @foreach ($comment->replies as $reply)
                                <div class="mb-2 p-2 border-l-2 border-gray-200">
                                    <div class="flex items-center">
                                        <img src="{{ $reply->user->profile_photo_url ?? asset('images/default-avatar.png') }}"
                                            alt="{{ $reply->user->name }}" class="w-6 h-6 rounded-full">
                                        <div class="ml-2">
                                            <p class="text-sm font-medium text-gray-900">{{ $reply->user->name }}</p>
                                            <p class="text-xs text-gray-500">{{ $reply->created_at->diffForHumans() }}
                                            </p>
                                        </div>
                                    </div>
                                    <p class="mt-1 text-sm text-gray-700">{{ $reply->content }}</p>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <script>
        // Toggle like for posts or comments
        function toggleLike(likeableType, likeableId) {
            fetch('/like', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    likeable_id: likeableId,
                    likeable_type: likeableType,
                }),
            })
                .then(response => response.json())
                .then(data => {
                    // Update the like count
                    const likeCount = document.getElementById(`like-count-${likeableType.toLowerCase()}-${likeableId}`);
                    likeCount.textContent = parseInt(likeCount.textContent) + (data.liked ? 1 : -1);
                });
        }

        // Toggle replies visibility
        function toggleReplies(commentId) {
            const repliesDiv = document.getElementById(`replies-${commentId}`);
            repliesDiv.classList.toggle('hidden');
        }

        // Show reply form
        function showReplyForm(commentId) {
            const replyForm = document.getElementById(`reply-form-${commentId}`);
            replyForm.classList.toggle('hidden');
        }
    </script>
</x-app-layout>