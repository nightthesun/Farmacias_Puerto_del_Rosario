<?php
namespace App\HelperServices;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class demandaDiariaEcu{
     
  public static function inicio_ecuacionDemandaDiaria(
    $id_producto,
    $tiempo_compra_1, $tiempo_compra_2,
    $tiempo_traspaso_1, $tiempo_traspaso_2,
    $tiempo_traslado_1, $tiempo_traslado_2,
    $tiempo_recepcion_1, $tiempo_recepcion_2,
    $envase,
    $z,$stock_actual
) {
    // Promedios de tiempos
    $L_compra = ($tiempo_compra_1 + $tiempo_compra_2) / 2;
    $L_traspaso = ($tiempo_traspaso_1 + $tiempo_traspaso_2) / 2;
    $L_traslado = ($tiempo_traslado_1 + $tiempo_traslado_2) / 2;
    $L_recepcion = ($tiempo_recepcion_1 + $tiempo_recepcion_2) / 2;

    // Obtener total de ventas y días transcurridos  
    $funcion_1 = self::demDiaPronosticada($id_producto,$envase);

    $total_ventas = $funcion_1->cantidad_total_vendida;
    $dias_transcurridos = $funcion_1->dias_con_venta;

    // Evitar división entre cero
    if ($dias_transcurridos <= 0) {
        $dias_transcurridos = 1;
    }

    // Demanda diaria
    $d = $total_ventas / $dias_transcurridos;

    // Tiempo de reposición
    $L = $L_compra + $L_traspaso + $L_traslado + $L_recepcion;

    // Demanda total durante L días
    $D = $d * $L;

    //---stock seguridad
    $suma_xi=0;
    $suma_xi3=0;
    $sumatoria_Xi1_Xi2_Xi_3=self::sumatoriaVentas($id_producto,$envase,$d);
    foreach ($sumatoria_Xi1_Xi2_Xi_3 as $key => $value) {
        $suma_xi=$suma_xi+$value->xi;
        $suma_xi3=$suma_xi3+$value->xi_prod_cuadrado;
    }
    $n=$dias_transcurridos-1;
    $varianza=$suma_xi3/$n;
    //desviacion estandar; 
    $s=round(sqrt($varianza), 2);
    $LT=sqrt($D);
    $SS=round($z*$s*$LT);
    //PUNTO DE PEDIDO
    $ROP=$D+$SS;
    //DEFICIT DE INVENTARIO
    $deficit=$ROP-$stock_actual;
    return $deficit;
}

private static function demDiaPronosticada($id_producto,$envase)
{
    $resultado = DB::table('ven__detalle_ventas as vdv')
    ->join('ven__recibos as vr', 'vr.id', '=', 'vdv.id_venta')
    ->where('vdv.id_producto', $id_producto)
    ->where('vr.anulado', 0)
    ->where('vdv.envase', $envase)
    ->selectRaw('
        COUNT(DISTINCT DATE(vr.created_at)) AS dias_con_venta,
        SUM(vdv.cantidad_venta) AS cantidad_total_vendida
    ')
    ->first();

    // Si no hay ventas devolver valores por defecto
    if (!$resultado || $resultado->cantidad_total_vendida == 0) {
        return (object)[
            'cantidad_total_vendida' => 0,
            'dias_con_venta' => 1
        ];
    }else{
        return $resultado;
    }  
}

private static function sumatoriaVentas($id_producto,$envase,$promedio){
   $resultado= DB::table('ven__detalle_ventas as vdv')
        ->join('ven__recibos as vr', 'vr.id', '=', 'vdv.id_venta')
        ->where('vdv.id_producto', $id_producto)
        ->where('vr.anulado', 0)
        ->where('vdv.envase', $envase)
        ->groupBy(DB::raw('DATE(vr.created_at)'))
        ->selectRaw('
            DATE(vr.created_at) AS fecha,
            ROUND(SUM(vdv.cantidad_venta), 2) AS xi,
            ROUND(SUM(vdv.cantidad_venta) - ?, 2) AS xi_prod,
            ROUND(POW(SUM(vdv.cantidad_venta) - ?, 2), 2) AS xi_prod_cuadrado
        ', [$promedio, $promedio])
        ->orderBy('fecha')
        ->get();
        if (count($resultado)>0 ) {
            return $resultado;
        }else{
            return (object)[
            'fecha' => null,
            'xi' => 0,
            'xi_prod' => 0,
            'xi_prod_cuadrado' => 0,
        ];
        }
    
}

}     