<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  @vite(['resources/css/app.css','resources/js/app.js'])
</head>
<body class="bg-gray-100">
  <div class="min-h-screen flex items-center justify-center">
    <form method="POST" action="{{ route('register.submit') }}" class="bg-white p-6 rounded shadow-md w-full max-w-md">
      @csrf
      <h1 class="text-xl font-semibold mb-4">Create an account</h1>

      @if($errors->any())
        <div class="text-red-600 mb-3">{{ $errors->first() }}</div>
      @endif

      <label class="block mb-3">
        <span class="text-sm font-medium">Name</span>
        <input type="text" name="name" value="{{ old('name') }}" required class="mt-1 block w-full rounded border p-2">
      </label>

      <label class="block mb-3">
        <span class="text-sm font-medium">Email</span>
        <input type="email" name="email" value="{{ old('email') }}" required class="mt-1 block w-full rounded border p-2">
      </label>

      <label class="block mb-3">
        <span class="text-sm font-medium">Password</span>
        <input type="password" name="password" required class="mt-1 block w-full rounded border p-2">
      </label>

      <label class="block mb-4">
        <span class="text-sm font-medium">Confirm Password</span>
        <input type="password" name="password_confirmation" required class="mt-1 block w-full rounded border p-2">
      </label>

      <button type="submit" class="w-full py-2 rounded bg-blue-600 text-white">Register</button>

      <p class="mt-4 text-sm text-center">
        Already have an account?
        <a href="{{ route('login') }}" class="text-blue-600 underline">Login</a>
      </p>
    </form>
  </div>
</body>
</html>
