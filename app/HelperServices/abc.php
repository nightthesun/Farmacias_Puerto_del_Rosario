<?php
namespace App\HelperServices;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;


class abc{
    // primero se deve enviar la lista de una sucursal de productos a la venta 
    public static function abc_index($id_sucursal){
        $abc_1 = self::metodoABC_paso_1($id_sucursal);
        $sumaA=0;
        $sumaB=0;
        $sumaC=0;

        $contarA=0;
        $contarB=0;
        $contarC=0;
        foreach ($abc_1 as $key => $value) {           
            $numero = (float)$value->porcentaje_participacion;
            if($value->clasificacion_abc=="A"){
                $sumaA=$sumaA+$numero;
                $contarA=$contarA+1;
            }
             if($value->clasificacion_abc=="B"){
                $sumaB=$sumaB+$numero;
                $contarB=$contarB+1;
            }
             if($value->clasificacion_abc=="C"){
                $sumaC=$sumaC+$numero;
                $contarC=$contarC+1;
            }
        }
        $contadorAll=$contarA+$contarB+$contarC;    
        
          $resultado = [];
            $n=1;
            $grupo="";
            $nro_pro=0;
            $porcentaje_prod=0;
            $acumulada=0;
            $costo=0;
            $acumulada_costo=0;
            while ($n <= 3) {
                    if($n==1){
                        $grupo="A";
                        $nro_pro=$contarA;
                        $porcentaje_prod=$contarA/$contadorAll;
                        $acumulada=$acumulada+$porcentaje_prod;
                        $costo=$sumaA;
                        $acumulada_costo=$acumulada_costo+$costo;
                    }
                    if($n==2){
                        $grupo="B";
                        $nro_pro=$contarB;
                        $porcentaje_prod=$contarB/$contadorAll;
                        $acumulada=$acumulada+$porcentaje_prod;
                        $costo=$sumaB;
                        $acumulada_costo=$acumulada_costo+$costo;
                    }
                    if($n==3){
                        $grupo="C";
                        $nro_pro=$contarC;
                        $porcentaje_prod=$contarC/$contadorAll;
                        $acumulada=$acumulada+$porcentaje_prod;
                        $costo=$sumaC;
                        $acumulada_costo=$acumulada_costo+$costo;
                    }

               
                $resultado[] = [
        'grupo'             => $grupo,
        'nro_prod'          => $nro_pro,
        'porcentaje_prod'   => round($porcentaje_prod, 2),
        'acumulada'         => round($acumulada, 2),
        'costo'             => round($costo, 2),
        'acumulada_costo'   => round($acumulada_costo, 2),
    ];
            $n++;    
            }

             
              
      
        return ['resultado'=>$resultado,
                'abc_1'=>$abc_1
                ];
       
    }
    

    private static function metodoABC_paso_1($id_sucursal){     

    $base = DB::table('ven__detalle_ventas as vdv')
    ->join('ven__recibos as vr', 'vdv.id_venta', '=', 'vr.id')
    ->select(
        'vdv.id_producto',
        'vdv.envase',
        'vr.id_sucursal',
        DB::raw('SUM(vdv.cantidad_venta) as total_vendido')
    )
    ->where('vr.id_sucursal', $id_sucursal)
    ->whereIn('vdv.envase', ['primario', 'secundario', 'terciario'])
    ->groupBy('vdv.id_producto', 'vdv.envase', 'vr.id_sucursal');

    $totales = DB::table(DB::raw("({$base->toSql()}) as base"))
    ->mergeBindings($base)
    ->select(
        'base.*',
        DB::raw('SUM(total_vendido) OVER () AS total_general')
    );

    $porcentajes = DB::table(DB::raw("({$totales->toSql()}) as totales"))
    ->mergeBindings($totales)
    ->select(
        'totales.*',
        DB::raw('ROUND((total_vendido / total_general) * 100, 2) AS porcentaje_participacion'),
        DB::raw('ROUND(SUM((total_vendido / total_general) * 100) OVER (ORDER BY total_vendido DESC), 2) AS porcentaje_acumulado')
    );

    $resultado = DB::table(DB::raw("({$porcentajes->toSql()}) as porcentajes"))
    ->mergeBindings($porcentajes)
    ->select(
        'id_producto',
        'envase',
        'id_sucursal',
        'total_vendido',
        'porcentaje_participacion',
        'porcentaje_acumulado',
        DB::raw("
            CASE
                WHEN porcentaje_acumulado <= 80 THEN 'A'
                WHEN porcentaje_acumulado <= 95 THEN 'B'
                ELSE 'C'
            END AS clasificacion_abc
        ")
    )
    ->orderByDesc('total_vendido')
    ->get();
    return $resultado;
    }
}