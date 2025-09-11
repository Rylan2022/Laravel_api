@extends('layouts.app')

@section('content')
  <div class="p-6">
    <h2 class="text-2xl">Welcome, {{ $user->name }}</h2>

    <form method="POST" action="{{ route('/logout') }}">
      @csrf
      <button class="mt-4 px-4 py-2 bg-red-600 text-white rounded">Logout</button>
    </form>
  </div>
@endsection
