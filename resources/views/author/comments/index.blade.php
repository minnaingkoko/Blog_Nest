<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Manage Comments') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Filter and Delete All Spam Row -->
            <div class="flex items-center justify-between mb-4">
                <!-- Filter by Status -->
                <form method="GET" action="{{ route('author.comments.index') }}" class="flex items-center">
                    <label for="filter" class="mr-2">Filter by Status:</label>
                    <select name="filter" onchange="this.form.submit()" class="border rounded p-2">
                        <option value="">All</option>
                        <option value="approved" {{ request('filter') === 'approved' ? 'selected' : '' }}>Approved
                        </option>
                        <option value="spam" {{ request('filter') === 'spam' ? 'selected' : '' }}>Spam</option>
                    </select>
                </form>

                <!-- Delete All Spam Button -->
                <form action="{{ route('author.comments.deleteAllSpam') }}" method="POST"
                    onsubmit="return confirm('Are you sure you want to delete all spam comments?')">
                    @csrf
                    <button type="submit" class="bg-red-600 text-white px-4 py-2 rounded">Delete All Spam</button>
                </form>
            </div>


            <!-- Comments Table -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    @if (session('success'))
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
                            {{ session('success') }}
                        </div>
                    @endif

                    <div class="overflow-x-auto">
                        <table class="min-w-full bg-white">
                            <thead>
                                <tr>
                                    <th
                                        class="px-6 py-3 border-b-2 border-gray-300 text-left text-sm leading-4 font-semibold">
                                        Post</th>
                                    <th
                                        class="px-6 py-3 border-b-2 border-gray-300 text-left text-sm leading-4 font-semibold">
                                        Comment</th>
                                    <th
                                        class="px-6 py-3 border-b-2 border-gray-300 text-left text-sm leading-4 font-semibold">
                                        Status</th>
                                    <th
                                        class="px-6 py-3 border-b-2 border-gray-300 text-left text-sm leading-4 font-semibold">
                                        Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($comments as $comment)
                                    <tr>
                                        <td class="px-6 py-4 border-b border-gray-300">
                                            <a href="{{ route('posts.show', $comment->post) }}"
                                                class="text-blue-600 hover:text-blue-800">
                                                {{ Str::limit($comment->post->title, 50) }}
                                            </a>
                                        </td>
                                        <td class="px-6 py-4 border-b border-gray-300">{{ $comment->content }}</td>
                                        <td class="px-6 py-4 border-b border-gray-300">
                                            <span
                                                class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $comment->is_spam ? 'bg-red-100 text-red-800' : 'bg-green-100 text-green-800' }}">
                                                {{ $comment->is_spam ? 'Spam' : 'Approved' }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 border-b border-gray-300">
                                            @if ($comment->is_spam)
                                                <form action="{{ route('author.comments.approve', $comment) }}"
                                                    method="POST" class="inline"
                                                    onsubmit="return confirm('Are you sure you want to approve this comment?')">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button type="submit"
                                                        class="text-green-600 hover:text-green-800">Approve</button>
                                                </form>
                                                <span class="mx-1">|</span>
                                            @endif
                                            <a href="{{ route('author.comments.edit', $comment) }}"
                                                class="text-blue-600 hover:text-blue-800">Edit</a>
                                            <span class="mx-1">|</span>
                                            <form action="{{ route('author.comments.destroy', $comment) }}"
                                                method="POST" class="inline"
                                                onsubmit="return confirm('Are you sure you want to delete this comment?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                    class="text-red-600 hover:text-red-800">Delete</button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="px-6 py-4 text-center text-gray-500">No comments
                                            found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="mt-4">
                        {{ $comments->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
