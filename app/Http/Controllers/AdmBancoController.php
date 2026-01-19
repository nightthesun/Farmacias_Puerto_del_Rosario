<?php

namespace App\Http\Controllers;

use App\Models\Adm_Banco;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class AdmBancoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $query = DB::table('adm__bancos as b')
    ->select(
        'b.id',
        'b.nombre',
        'b.sigla',
        'b.activo',
        'b.data',
        'b.prioridad'
    )
    ->orderBy('b.id', 'asc')
    ->paginate(15);  
           
            return 
            [
                    'pagination'=>
                        [
                            'total'         =>    $query->total(),
                            'current_page'  =>    $query->currentPage(),
                            'per_page'      =>    $query->perPage(),
                            'last_page'     =>    $query->lastPage(),
                            'from'          =>    $query->firstItem(),
                            'to'            =>    $query->lastItem(),
                        ] ,
                    'query'=>$query,
            ]; 

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator=Validator::make($request->all(),['nombre'=>'unique:adm__bancos']);

        //dd($validator->errors());
        
        if($validator->fails())
        {
            return 'error';
        }
        
        $banco = new Adm_Banco();

        $banco->nombre=$request->nombre;
        $banco->id_usuario_registra=auth()->user()->id;
        $banco->save();
        return ['idbanco'=>$banco->id];
    }

    public function create_banco(Request $request){
        try {
              // Iniciar una transacción
        DB::beginTransaction();
        $nombre_banco= $request->nombre_banco;
        $sigla=$request->sigla;
        if ($nombre_banco==""||$nombre_banco==null) {
            $nombre_banco="Banco sin nombre";
        }

        if ($sigla==""||$sigla==null) {
            $nombre_banco="Sin sigla";
        }
        $name = Str::upper($nombre_banco);
        $sig = Str::upper($sigla);

        $banco = new Adm_Banco();

        $banco->nombre=$name;
        $banco->sigla=$sig;
        $banco->id_usuario_registra=auth()->user()->id;
        $banco->save();
        $id_1=$banco->id;
 //1=add, 2=delete, 3=create, 4=edit, 5=show
         $fechaActual = Carbon::now(); // Obtiene la fecha y hora actual
            $datos = [
                'id_modulo' => $request->id_modulo,
                'id_sub_modulo' => $request->id_sub_modulo,
                'accion' => 3,
                'descripcion' => $request->des,          
                'user_id' =>auth()->user()->id, 
                'created_at'=>$fechaActual,
                'id_movimiento'=>$id_1,   
            ];        
            DB::table('log__sistema')->insert($datos);

           DB::commit();
           return 0;    
        } catch (\Throwable $th) {
             DB::rollback();
            return $th ;
        }
    }

    
    public function edit(Request $request)
    {
        try {
             DB::beginTransaction();
        $nombre_banco= $request->nombre_banco;
        $sigla=$request->sigla;
        if ($nombre_banco==""||$nombre_banco==null) {
            $nombre_banco="Banco sin nombre";
        }
        if ($sigla==""||$sigla==null) {
            $nombre_banco="Sin sigla";
        }
          $name = Str::upper($nombre_banco);
        $sig = Str::upper($sigla);
      $banco = Adm_Banco::findOrFail($request->id);

        $banco->nombre=$name;
        $banco->sigla=$sig;
        $banco->id_usuario_modifica=auth()->user()->id;
        $banco->save();

        //1=add, 2=delete, 3=create, 4=edit, 5=show
         $fechaActual = Carbon::now(); // Obtiene la fecha y hora actual
            $datos = [
                'id_modulo' => $request->id_modulo,
                'id_sub_modulo' => $request->id_sub_modulo,
                'accion' => 4,
                'descripcion' => $request->des,          
                'user_id' =>auth()->user()->id, 
                'created_at'=>$fechaActual,
                'id_movimiento'=>$request->id,   
            ];        
            DB::table('log__sistema')->insert($datos);

            DB::commit(); 
               return 0;             
        } catch (\Throwable $th) {
               DB::rollback();
            return $th;
        }       
    }

    public function activar_or_desactivar(Request $request){
                $banco = Adm_Banco::findOrFail($request->id);

        $banco->activo=$request->puntero;
       
        $banco->save();
    }

    public function selectBanco()
    {
        $bancos=Adm_Banco::select('nombre','id')
                    ->orderby('id','asc')
                    ->get();
        return $bancos;
        

    }
}
