<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>@yield('title','Sistem Informasi Pertanahan')</title>
  <link href="https://unpkg.com/tailwindcss@^2/dist/tailwind.min.css" rel="stylesheet">
  <script src="https://cdnjs.cloudflare.com/ajax/libs/feather-icons/4.28.0/feather.min.js"></script>
</head>
<body class="bg-gray-100 text-gray-800">
  <nav class="bg-white shadow">
    <div class="max-w-7xl mx-auto px-4 py-3 flex justify-between items-center">
      <div class="flex items-center space-x-4">
        <a href="{{ route('home') }}" class="text-xl font-semibold">Sistem Informasi Pertanahan</a>
        <a href="{{ route('leaflet') }}" class="text-sm text-gray-600 hover:text-gray-900">Peta Lahan</a>
      </div>
      <div>
        @auth
          <span class="mr-3">Halo, {{ auth()->user()->name }}</span>
          <form method="POST" action="{{ route('logout') }}" class="inline">@csrf<button class="bg-red-500 text-white px-3 py-1 rounded">Logout</button></form>
        @else
          <a href="{{ route('login') }}" class="text-sm mr-2">Login</a>
          <a href="{{ route('register') }}" class="text-sm bg-blue-600 text-white px-3 py-1 rounded">Register</a>
        @endauth
      </div>
    </div>
  </nav>

  <main class="max-w-7xl mx-auto p-6">
    @yield('content')
  </main>

  <script>feather.replace()</script>
</body>
</html>