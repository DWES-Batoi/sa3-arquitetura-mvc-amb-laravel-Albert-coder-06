

@extends('layouts.app')

@section('title', "Guia d'estadis")

@section('content')


  <h1 class="text-3xl font-bold text-blue-800 mb-6">Guia d'estadis</h1>


  @if (session('success'))
    <div class="bg-green-100 text-green-700 p-2 mb-4">

    </div>
  @endif

  
  <p class="mb-4">
    <a href="{{ route('estadis.create') }}"
       class="bg-blue-600 text-white px-3 py-2 rounded">
      Nou estadi
    </a>
  </p>


  <table class="w-full border-collapse border border-gray-300">
    <tbody>

        @foreach($estadis as $key => $estadi)
        <tr class="hover:bg-gray-100"> {{-- Fila de la tabla, se resalta al pasar el ratón --}}

            <td class="border border-gray-300 p-2">

                <a href="{{ route('estadis.show', $key) }}" class="text-blue-700 hover:underline">
                    {{ $estadi['nombre'] }} 
                </a>
            </td>
            
            <td class="border border-gray-300 p-2">
                {{ $estadi['ciudad'] }}
            </td>

            <td class="border border-gray-300 p-2">
                {{ $estadi['capacidad'] }}
            </td>

            <td class="border border-gray-300 p-2">
                {{ $estadi['equipo'] }}
            </td>

        </tr>
        @endforeach
    </tbody>
  </table>
@endsection 