{{-- Esta vista también extiende del layout principal --}}
@extends('layouts.app')

{{-- Título de la pestaña para la vista de detalle --}}
@section('title', "Detall d'Equip")

{{-- Contenido principal de la página de detalle --}}
@section('content')
  {{-- Componente Blade "equip" al que le pasamos los datos del equipo.
       :nom, :estadi y :titols son atributos que el componente recibirá como variables. --}}
  <x-equip :nom="$equip['nom']"
           :estadi="$equip['estadi']"
           :titols="$equip['titols']"/>
@endsection