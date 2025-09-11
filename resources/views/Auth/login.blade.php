<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100">
  <div class="min-h-screen flex items-center justify-center">
    @if(session('error')) <div>{{session('error')}}</div> @endif
    <form method="POST" action="{{ route('login.submit') }}" class="bg-white p-6 rounded shadow-md w-full max-w-md">
      @csrf
      <h1 class="text-xl font-semibold mb-4">Sign in</h1>

      @if($errors->any())
        <div class="text-red-600 mb-3">{{ $errors->first() }}</div>
      @endif

      <label class="block mb-2">
        <span class="text-sm font-medium">Email</span>
        <input type="email" name="email" value="{{ old('email') }}" required class="mt-1 block w-full rounded border p-2" />
      </label>

      <label class="block mb-4">
        <span class="text-sm font-medium">Password</span>
        <input type="password" name="password" required class="mt-1 block w-full rounded border p-2" />
      </label>

      <button type="submit" class="w-full py-2 rounded bg-blue-600 text-white">Sign in</button>
    </form>
  </div>
</body>
</html>
