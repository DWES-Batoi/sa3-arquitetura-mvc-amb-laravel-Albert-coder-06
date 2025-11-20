<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;          // Para gestionar la petición HTTP (formularios, etc.)
use Illuminate\Support\Facades\Session; // Para trabajar con la sesión

class EquipController extends Controller
{
    // Array de equipos por defecto (se usará si no hay nada en sesión)
    public $equips = [
        ['nom' => 'Barça Femení',      'estadi' => 'Camp Nou',               'titols' => 30],
        ['nom' => 'Atlètic de Madrid', 'estadi' => 'Cívitas Metropolitano',  'titols' => 10],
        ['nom' => 'Real Madrid Femení','estadi' => 'Alfredo Di Stéfano',     'titols' => 5],
    ];

    public function index()
    {
        // Recupera 'equips' de sesión o usa el array por defecto de la clase
        $equips = Session::get('equips', $this->equips);

        // Carga la vista resources/views/equips/index.blade.php
        // y le pasa el array $equips compactado
        return view('equips.index', compact('equips'));
    }

      public function show(int $id)
    {
        // Recupera la lista de equipos desde sesión o usa la de por defecto
        $equips = Session::get('equips', $this->equips);

        // Si no existe el índice indicado, devolvemos error 404
        abort_if(!isset($equips[$id]), 404);

        // Guarda el equipo concreto en la variable $equip
        $equip = $equips[$id];

        // Carga la vista equips.show y le pasa $equip
        return view('equips.show', compact('equip'));
    }

     public function create()
    {
        // Devuelve la vista con el formulario para crear un nuevo equipo
        return view('equips.create');
    }

     public function store(Request $request)
    {
        // Valida los datos del formulario según las reglas indicadas
        $validated = $request->validate([
            'nom'    => 'required|min:3',      // obligatorio y mínimo 3 caracteres
            'estadi' => 'required',            // obligatorio
            'titols' => 'required|integer|min:0', // obligatorio, número entero, mínimo 0
        ]);

        // Obtiene la lista actual de equipos desde la sesión (o la de por defecto)
        $equips = Session::get('equips', $this->equips);

        // Añade el nuevo equipo validado al final del array de equipos
        $equips[] = $validated;

        // Guarda el array actualizado en la sesión bajo la clave 'equips'
        Session::put('equips', $equips);

        // Redirige a la ruta equips.index y envía un mensaje flash de éxito
        return redirect()
            ->route('equips.index')
            ->with('success', 'Equip afegit correctament!');
    }

}