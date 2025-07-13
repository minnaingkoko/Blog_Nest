@extends('layouts.admin')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold text-gray-900 mb-8">User Details</h1>

    <div class="bg-white shadow rounded-lg overflow-hidden">
        <div class="p-6">
            <div class="flex items-center space-x-6 mb-8">
                <div class="flex-shrink-0 h-20 w-20 rounded-full bg-gray-300 flex items-center justify-center">
                    @if($user->avatar)
                        <img src="{{ Storage::url($user->avatar) }}" alt="{{ $user->name }}" class="h-full w-full rounded-full object-cover">
                    @else
                        <span class="text-gray-600 text-2xl">{{ substr($user->name, 0, 1) }}</span>
                    @endif
                </div>
                <div>
                    <h2 class="text-2xl font-bold text-gray-900">{{ $user->name }}</h2>
                    <p class="text-sm text-gray-500">{{ $user->email }}</p>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                <div>
                    <h3 class="text-sm font-medium text-gray-500 mb-2">Role</h3>
                    <p class="text-lg font-medium text-gray-900">{{ $user->role->name }}</p>
                </div>
                <div>
                    <h3 class="text-sm font-medium text-gray-500 mb-2">Joined At</h3>
                    <p class="text-lg font-medium text-gray-900">{{ $user->created_at->format('M d, Y') }}</p>
                </div>
            </div>

            <div class="mt-8 pt-6 border-t border-gray-200">
                <a href="{{ route('admin.users.index') }}" class="text-blue-600 hover:text-blue-800">
                    ← Back to all users
                </a>
            </div>
        </div>
    </div>
</div>
@endsection