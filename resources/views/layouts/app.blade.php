<!DOCTYPE html>
<html lang="ca"> 
<head>
  <meta charset="UTF-8" /> 
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/> 

  <title>@yield('title','Guia de futbol femení')</title>

 
  @vite(['resources/css/app.css'])
</head>
<body class="font-sans bg-gray-100 text-gray-900"> 
  <header class="bg-blue-800 text-white p-4">

    @include('partials.menu')
  </header>

  <main class="container mx-auto p-6"> 

    @yield('content')
  </main>

  <footer class="bg-blue-800 text-white text-center p-4"> {{-- Pie de página --}}
    <p>&copy; 2025 Guia de Futbol Femení</p>
  </footer>
</body>
</html>