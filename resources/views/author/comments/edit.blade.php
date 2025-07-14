<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Comment') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <!-- Form for editing a comment -->
                    <form action="{{ route('author.comments.update', $comment) }}" method="POST">
                        @csrf
                        @method('PATCH')

                        <!-- Comment Content -->
                        <div class="mb-4">
                            <label for="content" class="block text-sm font-medium text-gray-700">Comment Content:</label>
                            <textarea name="content" id="content" rows="5" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">{{ $comment->content }}</textarea>
                        </div>

                        <!-- Spam Checkbox -->
                        <div class="mb-4">
                            <label for="is_spam" class="flex items-center">
                                <input type="checkbox" name="is_spam" id="is_spam" value="1" {{ $comment->is_spam ? 'checked' : '' }} class="rounded border-gray-300 text-blue-600 shadow-sm focus:ring-blue-500">
                                <span class="ml-2 text-sm text-gray-700">Mark as Spam</span>
                            </label>
                        </div>

                        <!-- Submit Button -->
                        <div class="flex items-center justify-end gap-4">
                            <a href="{{ route('author.comments.index') }}" class="text-gray-600 hover:text-gray-800">Cancel</a>
                            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                Update Comment
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>