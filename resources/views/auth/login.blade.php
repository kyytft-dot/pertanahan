@extends('layouts.app')
@section('title','Login')
@section('content')
<div class="max-w-md mx-auto bg-white p-6 rounded shadow">
  <h3 class="text-lg mb-4">Login</h3>
  <form method="POST" action="{{ route('login') }}">
    @csrf
    <label class="block mb-2">NIK</label>
    <input name="nik" value="{{ old('nik') }}" class="w-full border p-2 rounded mb-3" required>

    <label class="block mb-2">Password</label>
    <input name="password" type="password" class="w-full border p-2 rounded mb-3" required>

    <button class="w-full bg-indigo-600 text-white py-2 rounded">Login</button>
  </form>
</div>
@endsection