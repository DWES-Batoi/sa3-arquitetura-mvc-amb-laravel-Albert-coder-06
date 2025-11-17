<!DOCTYPE html>
<html lang="ca"> {{-- Idioma del documento: catalán --}}
<head>
  <meta charset="UTF-8" /> {{-- Codificación de caracteres (UTF-8) --}}
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/> {{-- Hace la página responsive --}}

  {{-- Título de la pestaña. Si la vista hija define la sección "title", se usa ese texto.
       Si no, se muestra "Guia de futbol femení" por defecto. --}}
  <title>@yield('title','Guia de futbol femení')</title>

  {{-- Carga los assets compilados por Vite (por ejemplo, CSS de la app) --}}
  @vite(['resources/css/app.css'])
</head>
<body class="font-sans bg-gray-100 text-gray-900"> {{-- Clases de Tailwind para estilo básico --}}
  <header class="bg-blue-800 text-white p-4"> {{-- Cabecera azul con texto blanco --}}
    {{-- Incluye la vista parcial del menú de navegación --}}
    @include('partials.menu')
  </header>

  <main class="container mx-auto p-6"> {{-- Contenedor central de la página --}}
    {{-- Aquí se insertará el contenido específico de cada vista hija.
         Las vistas hijas definen @section('content') ... @endsection,
         y todo ese bloque se "inyecta" en este hueco. --}}
    @yield('content')
  </main>

  <footer class="bg-blue-800 text-white text-center p-4"> {{-- Pie de página --}}
    <p>&copy; 2025 Guia de Futbol Femení</p>
  </footer>
</body>
</html>