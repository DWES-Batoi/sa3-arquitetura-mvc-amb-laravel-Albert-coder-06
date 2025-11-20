{{-- Indica que esta vista extiende (hereda) de layouts.app --}}
@extends('layouts.app')

{{-- Define el contenido de la sección "title" que usará el layout en <title> --}}
@section('title', "Guia d'Equips")

{{-- Abre la sección "content" que se insertará en @yield('content') del layout --}}
@section('content')

  {{-- Título principal de la página --}}
  <h1 class="text-3xl font-bold text-blue-800 mb-6">Guia d'Equips</h1>

  {{-- Si existe un mensaje de éxito en la sesión, lo mostramos en un recuadro verde --}}
  @if (session('success'))
    <div class="bg-green-100 text-green-700 p-2 mb-4">
      {{ session('success') }} {{-- Muestra el texto del mensaje --}}
    </div>
  @endif

  {{-- Enlace para ir al formulario de creación de un nuevo equipo --}}
  <p class="mb-4">
    <a href="{{ route('equips.create') }}"
       class="bg-blue-600 text-white px-3 py-2 rounded">
      Nou equip
    </a>
  </p>

  {{-- Tabla que mostrará el listado de equipos --}}
  <table class="w-full border-collapse border border-gray-300">
    <tbody>
      {{-- Recorre todos los equipos recibidos desde el controlador --}}
      @foreach($equips as $key => $equip)
        <tr class="hover:bg-gray-100"> {{-- Fila de la tabla, se resalta al pasar el ratón --}}
          {{-- Primera celda: nombre del equipo, enlazando a su página de detalle --}}
          <td class="border border-gray-300 p-2">
            {{-- route('equips.show', $key) genera la URL para ver el detalle del equipo con índice $key --}}
            <a href="{{ route('equips.show', $key) }}"
               class="text-blue-700 hover:underline">
              {{ $equip['nom'] }} {{-- Muestra el nombre del equipo --}}
            </a>
          </td>

          {{-- Segunda celda: estadio del equipo --}}
          <td class="border border-gray-300 p-2">
            {{ $equip['estadi'] }}
          </td>

          {{-- Tercera celda: número de títulos del equipo --}}
          <td class="border border-gray-300 p-2">
            {{ $equip['titols'] }}
          </td>
        </tr>
      @endforeach
    </tbody>
  </table>
@endsection {{-- Cierra la sección "content" --}}