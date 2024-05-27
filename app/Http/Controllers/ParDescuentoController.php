<?php

namespace App\Http\Controllers;

use App\Models\Par_Descuento;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ParDescuentoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

  

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Par_Descuento $par_Descuento)
    {
        //
    }
    public function listarTipoDescuentos(){
        // Obtener los descuentos desde la base de datos
        $descuentos = DB::table('prod__tipo_descuentos')
                        ->select('id', 'aplica_a', 'subcategorias', 'activo')
                        ->where('activo', '=', 1)
                        ->get();
    
        // Crear un nuevo array para almacenar los resultados
        $result = [];
    
        // Iterar sobre cada descuento
        foreach ($descuentos as $descuento) {
            // Explode para dividir las subcategorías
            $metodos = explode('|', $descuento->subcategorias);
            
            // Crear entradas individuales para cada método
            foreach ($metodos as $metodo) {
                $result[] = [
                    'id' => $descuento->id,
                    'aplica_a' => $descuento->aplica_a,
                    'metodo' => $metodo
                ];
            }
        }
    
    
 // Retornar los resultados (opcional)
 return ['subcategoria' => $result, 'descuentos' => $descuentos];
    }
    

    

}
