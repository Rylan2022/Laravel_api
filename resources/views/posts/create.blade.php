<!DOCTYPE html>
<html>

<head>
    <title>Create Post</title>
</head>

<body>
    <h1>Create New Post</h1>

    <form action="{{ route('posts.save') }}" method="POST">
        @csrf
        <p>Title: <input type="text" name="title" value="{{ old('title') }}"></p>
        <p>Content: <textarea name="content">{{ old('content') }}</textarea></p>
        <button type="submit">Save</button>
    </form>

</body>

</html>