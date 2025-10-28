@extends('layouts.app')
@section('title','Profil')
@section('content')
<div class="bg-white p-6 rounded shadow max-w-3xl mx-auto">
  <h2 class="text-xl font-semibold mb-3">Profil Saya</h2>
  <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
    <div>
      <p><strong>Nama</strong><br>{{ $user->name }}</p>
      <p class="mt-2"><strong>NIK</strong><br>{{ $user->nik }}</p>
      <p class="mt-2"><strong>Email</strong><br>{{ $user->email ?? '-' }}</p>
    </div>
    <div>
      <p><strong>Alamat</strong><br>{{ $user->alamat ?? '-' }}</p>
      <p class="mt-2"><strong>Telepon</strong><br>{{ $user->phone ?? '-' }}</p>
    </div>
  </div>
</div>
@endsection