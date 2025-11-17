<nav> {{-- Etiqueta de navegación principal --}}
  <ul class="flex space-x-4"> {{-- Lista horizontal con separación entre elementos --}}
    <li>
      {{-- Enlace a la página de inicio ('/') --}}
      <a class="text-white hover:underline" href="/">Inici</a>
    </li>
    <li>
      {{-- Enlace a la lista de equipos usando el nombre de ruta "equips.index" --}}
      <a class="text-white hover:underline" href="{{ route('equips.index') }}">
        Guia d'Equips
      </a>
    </li>
    <li>
      {{-- Enlace de ejemplo para futuros listados de estadios --}}
      <a class="text-white hover:underline" href="#">
        Llistat d'Estadis
      </a>
    </li>
  </ul>
</nav>