@extends('layouts.app')
@section('title','Register')
@section('content')
<div class="max-w-md mx-auto bg-white p-6 rounded shadow">
  <h3 class="text-lg mb-4">Daftar Akun (NIK)</h3>
  <form method="POST" action="{{ route('register') }}">
    @csrf
    <label class="block mb-2">Nama</label>
    <input name="name" value="{{ old('name') }}" class="w-full border p-2 rounded mb-3" required>

    <label class="block mb-2">NIK</label>
    <input name="nik" value="{{ old('nik') }}" class="w-full border p-2 rounded mb-3" required>

    <label class="block mb-2">Email (opsional)</label>
    <input name="email" value="{{ old('email') }}" class="w-full border p-2 rounded mb-3">

    <label class="block mb-2">Password</label>
    <input name="password" type="password" class="w-full border p-2 rounded mb-3" required>

    <label class="block mb-2">Konfirmasi Password</label>
    <input name="password_confirmation" type="password" class="w-full border p-2 rounded mb-3" required>

    <button class="w-full bg-blue-600 text-white py-2 rounded">Daftar</button>
  </form>
</div>
@endsection