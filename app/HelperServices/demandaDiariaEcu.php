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
    $tiempo_recepcion_1, $tiempo_recepcion_2
) {
    // Promedios de tiempos
    $L_compra = ($tiempo_compra_1 + $tiempo_compra_2) / 2;
    $L_traspaso = ($tiempo_traspaso_1 + $tiempo_traspaso_2) / 2;
    $L_traslado = ($tiempo_traslado_1 + $tiempo_traslado_2) / 2;
    $L_recepcion = ($tiempo_recepcion_1 + $tiempo_recepcion_2) / 2;

    // Obtener total de ventas y días transcurridos  
    $funcion_1 = self::demDiaPronosticada($id_producto);

    $total_ventas = $funcion_1->total_ventas;
    $dias_transcurridos = $funcion_1->dias_transcurridos;

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

    return $D;
}

private static function demDiaPronosticada($id_producto)
{
    $resultado = DB::table('ven__detalle_ventas as vdv')
        ->join('ven__recibos as vr', 'vdv.id_venta', '=', 'vr.id')
        ->where('vdv.id_producto', $id_producto)
        ->where('vr.anulado', 0)
        ->selectRaw('COUNT(*) AS total_ventas')
        ->selectRaw('MIN(vr.created_at) AS fecha_minima')
        ->selectRaw('MAX(vr.created_at) AS fecha_maxima')
        ->selectRaw('DATEDIFF(MAX(vr.created_at), MIN(vr.created_at)) AS dias_transcurridos')
        ->first();

    // Si no hay ventas devolver valores por defecto
    if (!$resultado || $resultado->total_ventas == 0) {
        return (object)[
            'total_ventas' => 0,
            'fecha_minima' => null,
            'fecha_maxima' => null,
            'dias_transcurridos' => 1
        ];
    }

    return $resultado;
}

}     