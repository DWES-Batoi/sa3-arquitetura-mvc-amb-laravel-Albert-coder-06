
@extends('layouts.app')


@section('title', 'Afegir nou estadi')


@section('content')
 
  <h1 class="text-2xl font-bold mb-4">Afegir nou estadi</h1>

  @if ($errors->any())
    <div class="bg-red-100 text-red-700 p-2 mb-4">
      <ul>
 
        @foreach ($errors->all() as $error)
          <li>{{ $error }}</li>
        @endforeach
      </ul>
    </div>
  @endif

  <form action="{{ route('estadis.store') }}" method="POST" class="space-y-4">

    {{-- Directiva Blade para incluir el token CSRF (seguridad contra ataques de formularios falsos) --}}
    @csrf

    {{-- Campo: nombre del estadio --}}
    <div>
      <label for="nombre" class="block font-bold">Nom de l'equip:</label>
      {{-- old('nom') rellena el campo con el valor anterior si la validación falla --}}
      <input type="text" name="nombre" id="nombre"
             value="{{ old('nombre') }}" class="border p-2 w-full">
    </div>

    {{-- Campo: ciudad --}}
    <div>
      <label for="ciudad" class="block font-bold">Estadi:</label>
      <input type="text" name="ciudad" id="ciudad"
             value="{{ old('ciudad') }}" class="border p-2 w-full">
    </div>

    {{-- Campo: capacidad --}}
    <div>
      <label for="capacidad" class="block font-bold">Títols:</label>
      <input type="number" name="capacidad" id="capacidad"
             value="{{ old('capacidad') }}" class="border p-2 w-full">
    </div>

    {{-- Campo: equipo --}}
    <div>
      <label for="equipo" class="block font-bold">Títols:</label>
      <input type="text" name="equipo" id="equipo"
             value="{{ old('equipo') }}" class="border p-2 w-full">
    </div>

    <button type="submit"
            class="bg-blue-600 text-white px-4 py-2 rounded">
      Afegir
    </button>
  </form>
@endsection