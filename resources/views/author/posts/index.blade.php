@extends('layouts.app')

@section('title', 'My Posts')

@section('content')
    <h1 class="mb-4">My Posts</h1>
    <a href="{{ route('author.posts.create') }}" class="btn btn-primary mb-3">Create New Post</a>
    @foreach ($posts as $post)
        <div class="card mb-4">
            <div class="card-body">
                <h2 class="card-title">{{ $post->title }}</h2>
                <p class="card-text">{{ Str::limit($post->excerpt, 200) }}</p>
                <a href="{{ route('author.posts.edit', $post) }}" class="btn btn-warning">Edit</a>
                <form action="{{ route('author.posts.destroy', $post) }}" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Delete</button>
                </form>
            </div>
        </div>
    @endforeach
    {{ $posts->links() }} <!-- Pagination -->
@endsection