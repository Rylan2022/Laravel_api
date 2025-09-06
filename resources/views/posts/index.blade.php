<!DOCTYPE html>
<html>
<head>
    <title>Posts</title>
</head>
<body>
    <h1>All Posts</h1>
    <a href="{{ route('posts.create') }}">Create New Post</a>

    @if(session('success'))
        <p style="color:green;">{{ session('success') }}</p>
    @endif

    <ul>
        @foreach($posts as $post)
            <li>
                <strong>{{ $post->title }}</strong> - {{ $post->content }}
                <a href="{{ route('posts.edit', $post->id) }}">Edit</a>
                <form action="{{ route('posts.destroy', $post->id) }}" method="POST" style="display:inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" onclick="return confirm('Delete this post?')">Delete</button>
                </form>
            </li>
        @endforeach
    </ul>
</body>
</html>
