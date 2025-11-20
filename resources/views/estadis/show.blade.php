{{-- Esta vista también extiende del layout principal --}}
@extends('layouts.app')

{{-- Título de la pestaña para la vista de detalle --}}
@section('title', "Detall d'estadi")

{{-- Contenido principal de la página de detalle --}}
@section('content')
  {{-- Componente Blade "equip" al que le pasamos los datos del equipo.
       :nom, :estadi y :titols son atributos que el componente recibirá como variables. --}}
  <x-estadi :nombre="$estadi['nombre']"
           :ciudad="$estadi['ciudad']"
           :capacidad="$estadi['capacidad']"
           :equipo="$estadi['equipo']"/>
@endsection