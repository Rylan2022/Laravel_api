<!DOCTYPE html>
<html>
<head>
    <title>Send Advanced Mail</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
</head>
<body>
<div class="container mt-5">
    <h1>Send Email</h1>
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif
    <form method="POST" action="{{ route('send.mail') }}" enctype="multipart/form-data">
        @csrf
        <div class="mb-3">
            <label>Email:</label>
            <input type="email" name="email" class="form-control" required>
            @error('email') <small class="text-danger">{{ $message }}</small> @enderror
        </div>
        <div class="mb-3">
            <label>Message:</label>
            <textarea name="message" class="form-control" required></textarea>
            @error('message') <small class="text-danger">{{ $message }}</small> @enderror
        </div>
        <div class="mb-3">
            <label>Attachment (optional):</label>
            <input type="file" name="attachment" class="form-control">
            @error('attachment') <small class="text-danger">{{ $message }}</small> @enderror
        </div>
        <button type="submit" class="btn btn-primary">Send Mail</button>
    </form>
</div>
</body>
</html>
