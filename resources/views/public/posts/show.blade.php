<x-guest-layout>

@section('content')
    <div class="py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm rounded-lg p-6">
                <h1 class="text-3xl font-bold mb-6">{{ $post->title }}</h1>
                <p class="text-gray-700">{{ $post->content }}</p>
                <a href="{{ route('home') }}" class="inline-block mt-6 px-4 py-2 bg-gray-500 text-white rounded hover:bg-gray-600">Back to Home</a>
            </div>
        </div>
    </div>
</x-guest-layout>