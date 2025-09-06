<!DOCTYPE html>
<html>

<head>
    <title>Edit Post</title>
</head>

<body>
    <h1>Edit Post</h1>

    <form action="{{ route('posts.save', $post->id) }}" method="POST">
        @csrf
        <p>Title: <input type="text" name="title" value="{{ $post->title }}"></p>
        <p>Content: <textarea name="content">{{ $post->content }}</textarea></p>
        <button type="submit">Save</button>
    </form>


</body>

</html>