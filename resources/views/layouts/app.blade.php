<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laravel 12 + Vite</title>

    <title>My LARAVEL APP</title>
</head>

<body>
    <h1>All Posts</h1>

    @if(isset($postdata) && $postdata->count())
    @foreach($postdata as $post)
    <h3>{{ $post->title }}</h3>
    <p>{{ $post->content }}</p>
    @endforeach
    @else
    <p>No posts found.</p>
    @endif
</body>

</html>