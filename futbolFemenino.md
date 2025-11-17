# Proyecto Laravel: Guia d’Equips de Futbol Femení 🏟️⚽  
*(hasta el apartado de vistas)*

> Este documento resume lo que hemos hecho en clase: repositorio, Laravel, rutas, controlador y **vistas**.  
> El código está simplificado pero respeta la explicación que hemos ido usando.

---

## Vista general del proyecto

```
  A[Navegador] --> B[Ruta (routes/web.php)]
  B --> C[Controlador EquipController]
  C --> D[Vistas Blade (equips/*)]
  C --> E[Sesión (equips en Session)]
```

Piensa siempre en este flujo:

**Navegador → Ruta → Controlador → Vista**

---

## 1. Enlazamos con GitHub Classroom

1. El profesorado os pasa un **enlace de GitHub Classroom**.
2. Aceptas la tarea → GitHub crea **tu repositorio**.
3. Clonas el repo en tu máquina (o en el entorno que usemos en el aula).

A partir de aquí, todo el código del proyecto lo trabajamos dentro de ese repo.

---

## 2. Preparamos Laravel con Docker y `make`

Antes de tocar el código, dejamos los contenedores limpios y levantamos el entorno.

```bash
# Parar servicios de Docker Compose (recomendado)
docker compose down

# Borrar contenedores antiguos (menos recomendable, pero más "limpio")
sudo docker rm -f $(sudo docker ps -aq)
```

- `docker compose down`: apaga los contenedores del proyecto.
- `docker rm -f ...`: borra **todos** los contenedores (es agresivo, cuidado).

Ahora construimos y arrancamos todo:

```bash
# Construir y levantar contenedores
make up

# Instalar Laravel (dependencias con Composer dentro del contenedor)
make install
```

- `make up`: crea/levanta los contenedores que necesitamos (PHP, etc.).
- `make install`: instala Laravel y sus dependencias en el proyecto.

> A partir de aquí ya tenemos un Laravel funcionando dentro de Docker.

---

## 3. Creamos las rutas (`routes/web.php`)

Editamos el archivo `routes/web.php` para definir las rutas de nuestra aplicación:

```php
<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EquipController;

// Ruta de bienvenida (GET /)
Route::get('/', fn() => "Benvingut a la Guia d'Equips de Futbol Femení!");

// Genera automáticamente varias rutas REST para 'equips'
Route::resource('equips', EquipController::class);
```

### ¿Qué hace `Route::resource`?

Laravel genera automáticamente un conjunto de rutas estándar (REST) para `equips`:

| Verbo HTTP | URL                  | Nombre de ruta   | Método del controlador |
| ---------- | -------------------- | ---------------- | ---------------------- |
| GET        | /equips              | equips.index     | index()                |
| GET        | /equips/create       | equips.create    | create()               |
| POST       | /equips              | equips.store     | store()                |
| GET        | /equips/{equip}      | equips.show      | show()                 |
| GET        | /equips/{equip}/edit | equips.edit      | edit()                 |
| PUT/PATCH  | /equips/{equip}      | equips.update    | update()               |
| DELETE     | /equips/{equip}      | equips.destroy   | destroy()              |

De momento nos interesan sobre todo: **index**, **show** y **create/store**.

📸 _Idea de imagen_:  
`![Esquema rutas de equips](img/rutas-equips.png)`  
(diagrama simple con las URLs y los métodos del controlador).

---

## 4. Creamos el controlador `EquipController.php`

Archivo: `app/Http/Controllers/EquipController.php`

```php
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
```

### 4.1 Método `index()` → listar equipos

```php
    // Lista equipos, index por convención se usa para listar recursos    
    public function index()
    {
        // Recupera 'equips' de sesión o usa el array por defecto de la clase
        $equips = Session::get('equips', $this->equips);

        // Carga la vista resources/views/equips/index.blade.php
        // y le pasa el array $equips compactado
        return view('equips.index', compact('equips'));
    }
```

### 4.2 Método `show($id)` → detalle de un equipo

```php
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
```

### 4.3 Método `create()` → formulario de alta

```php
    public function create()
    {
        // Devuelve la vista con el formulario para crear un nuevo equipo
        return view('equips.create');
    }
```

### 4.4 Método `store(Request $request)` → guardar equipo nuevo

```php
    // Recibe datos del formulario    
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
```

📸 _Idea de imagen_:  
`![Flujo create/store](img/flujo-create-store.png)`  
(esquema: formulario → store() → sesión → redirección a lista).

---

## 5. Creamos las vistas (Blade)

Ahora trabajamos en `resources/views`.

---

### 5.1 Plantilla base `layouts/app.blade.php`

Archivo: `resources/views/layouts/app.blade.php`

```blade
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
```

#### ¿Qué hace realmente `@yield('content')` aquí?

En este layout (`layouts/app.blade.php`), `@yield('content')` es **un hueco** para el contenido principal de cada página.

- Las vistas hijas (`equips.index`, `equips.show`, `equips.create`, etc.) ponen su contenido dentro de:

  ```blade
  @section('content')
      <!-- contenido de esa página -->
  @endsection
  ```

- Cuando Laravel renderiza la vista, **sustituye** `@yield('content')` por lo que haya dentro de `@section('content')` de esa vista concreta.

Pasa lo mismo con `@yield('title')`:  
cada vista puede definir `@section('title', 'Texto del título')` y ese texto se inyecta en la etiqueta `<title>` del `<head>`.

---

### 5.2 Creamos las vistas concretas

#### 5.2.1 Menú parcial `partials/menu.blade.php`

Archivo: `resources/views/partials/menu.blade.php`

```blade
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
```

---

#### 5.2.2 Lista de equipos `equips/index.blade.php`

Archivo: `resources/views/equips/index.blade.php`

```blade
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
```

📸 _Idea de imagen_:  
`![Lista de equipos en el navegador](img/lista-equips.png)`

---

#### 5.2.3 Detalle de un equipo `equips/show.blade.php`

Archivo: `resources/views/equips/show.blade.php`

```blade
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
```

---

#### 5.2.4 Formulario de creación `equips/create.blade.php`

Archivo: `resources/views/equips/create.blade.php`

```blade
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
```

---

### 5.3 Componente `components/equip.blade.php`

Archivo: `resources/views/components/equip.blade.php`

```blade
{{-- Contenedor del componente de equipo con estilos básicos --}}
<div class="equip border rounded-lg shadow-md p-4 bg-white">
  {{-- Nombre del equipo en grande y en azul --}}
  <h2 class="text-xl font-bold text-blue-800">{{ $nom }}</h2>

  {{-- Línea que muestra el estadio del equipo --}}
  <p>
    <strong>Estadi:</strong> {{ $estadi }}
  </p>

  {{-- Línea que muestra el número de títulos --}}
  <p>
    <strong>Títols:</strong> {{ $titols }}
  </p>
</div>
```

- Este componente recibe las variables `$nom`, `$estadi` y `$titols`.
- Lo usamos desde otras vistas como:

  ```blade
  <x-equip :nom="$equip['nom']"
           :estadi="$equip['estadi']"
           :titols="$equip['titols']" />
  ```

---

### 5.4 Estilos básicos `resources/css/equips.css` (opcional)

```css
body { font-family: system-ui, Arial, sans-serif; }
h1 { color: darkblue; }

nav ul { list-style: none; padding: 0; }
nav li { display: inline; margin-right: 15px; }
nav a { text-decoration: none; color: white; }
nav a:hover { text-decoration: underline; }

.equip {
  border: 1px solid #ddd;
  padding: 10px;
  margin: 10px 0;
  border-radius: 5px;
}
.equip h2 { margin: 0; color: darkblue; }
```

---