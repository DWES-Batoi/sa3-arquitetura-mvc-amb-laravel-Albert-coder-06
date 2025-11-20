{{-- Extiende del layout principal --}}
@extends('layouts.app')

{{-- Título de la pestaña para la vista de creación --}}
@section('title', 'Afegir nou equip')

{{-- Contenido principal de la página --}}
@section('content')
  {{-- Título del formulario --}}
  <h1 class="text-2xl font-bold mb-4">Afegir nou equip</h1>

  {{-- Si hay errores de validación, los mostramos en un recuadro rojo --}}
  @if ($errors->any())
    <div class="bg-red-100 text-red-700 p-2 mb-4">
      <ul>
        {{-- Recorre todos los mensajes de error y los muestra en una lista --}}
        @foreach ($errors->all() as $error)
          <li>{{ $error }}</li>
        @endforeach
      </ul>
    </div>
  @endif

  {{-- Formulario para crear un nuevo equipo.
       action: ruta que procesa el formulario (equips.store).
       method: POST porque estamos enviando datos para guardar. --}}
  <form action="{{ route('equips.store') }}" method="POST" class="space-y-4">
    {{-- Directiva Blade para incluir el token CSRF (seguridad contra ataques de formularios falsos) --}}
    @csrf

    {{-- Campo: nombre del equipo --}}
    <div>
      <label for="nom" class="block font-bold">Nom de l'equip:</label>
      {{-- old('nom') rellena el campo con el valor anterior si la validación falla --}}
      <input type="text" name="nom" id="nom"
             value="{{ old('nom') }}" class="border p-2 w-full">
    </div>

    {{-- Campo: estadio --}}
    <div>
      <label for="estadi" class="block font-bold">Estadi:</label>
      <input type="text" name="estadi" id="estadi"
             value="{{ old('estadi') }}" class="border p-2 w-full">
    </div>

    {{-- Campo: títulos --}}
    <div>
      <label for="titols" class="block font-bold">Títols:</label>
      <input type="number" name="titols" id="titols"
             value="{{ old('titols') }}" class="border p-2 w-full">
    </div>

    {{-- Botón para enviar el formulario --}}
    <button type="submit"
            class="bg-blue-600 text-white px-4 py-2 rounded">
      Afegir
    </button>
  </form>
@endsection