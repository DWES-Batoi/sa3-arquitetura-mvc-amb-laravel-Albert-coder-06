<?php
    namespace App\Http\Controllers;
    use Illuminate\Http\Request;          // Para gestionar la petición HTTP (formularios, etc.)
    use Illuminate\Support\Facades\Session; 

    class EstadisController extends Controller{
        public $estadis = [ 
            ["nombre" => "Estadi Johan Cruyff",
            "ciudad" => "Sant Joan Despí",
            "capacidad" => 6000,
            "equipo" => "FC Barcelona Femení"],

            ["nombre" => "Centro Deportivo Wanda Alcalá de Henares",
            "ciudad" => "Alcalá de Henares",
            "capacidad" => 2800,
            "equipo" => "Atlètic de Madrid Femení"],

            ["nombre" => "Estadio Alfredo Di Stéfano",
            "ciudad" => "Madrid",
            "capacidad" => 6000,
            "equipo" => "Real Madrid Femení"]
            
            
        ];

        public function index() {
            $estadis = Session::get("estadis", $this->estadis);

            return view('estadis.index', compact('estadis'));

        }

        public function show(int $nom) {
            $estadis = Session::get('estadis', $this->estadis);

            abort_if(!isset($estadis[$nom]), 404);

            $estadi = $estadis[$nom];

            return view('estadis.show', compact('estadi'));
        }

        public function create() {
            return view("estadis.create");
        }

        public function store(Request $request) {
            $formularioValidado = $request->validate([
                'nombre' => 'required|min:3',
                'ciudad' => 'required|min:2',
                'capacidad' => 'required|integer|min:0',
                'equipo' => 'required|min:3'
            ]);

            $estadis = Session::get('estadis', $this->estadis);

            $estadis[] = $formularioValidado;

            Session::put('estadis', $estadis);

            return redirect()->route('estadis.index')->with('success', 'Estadio añadido correctamente');


        }
    }

?>