<?php
namespace App\HelperServices;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class canal{

    public static function indexCanal($id_producto){

          $canal = self::queryTabla($id_producto);
          $resultado = [];
         foreach ($canal as $key => $value) {
           unset($value->mes);
        }    
         sort($canal);
         //media Me=(N+1)/2
         //cuartil Qx=((N+1)/4)*#Q
         //cuartil Q1=((N+1)/4)*1,Q2=((N+1)/4)*2,Q3=((N+1)/4)*3
         // como se toma cinco años como dato estatico N=5
         $N=5;
         $NmasUno=5+1;        
         $Me=round((($NmasUno)/2),2);
         $Q1=round(((($NmasUno)/4)*1),2);
         $Q2=round(((($NmasUno)/4)*2),2);
         $Q3=round(((($NmasUno)/4)*3),2);

         
          return $canal;
    }

private static function queryTabla($id_producto)
{
    // la consulta solo funciona de esta manera ya que las otras manera no funciona ni en laravel build 
    $anio_actual_1 = now()->year;
    $anio_actual_2 = now()->year-1;
    $anio_actual_3 = now()->year-2;
    $anio_actual_4 = now()->year-3;
    $anio_actual_5 = now()->year-4;

$sql = "
SELECT 
    mes.mes,
    COALESCE(SUM(CASE WHEN YEAR(vr.created_at) = YEAR(CURDATE())     THEN vdv.cantidad_venta END), 0) AS `$anio_actual_1`,
    COALESCE(SUM(CASE WHEN YEAR(vr.created_at) = YEAR(CURDATE()) - 1 THEN vdv.cantidad_venta END), 0) AS `$anio_actual_2`,
    COALESCE(SUM(CASE WHEN YEAR(vr.created_at) = YEAR(CURDATE()) - 2 THEN vdv.cantidad_venta END), 0) AS `$anio_actual_3`,
    COALESCE(SUM(CASE WHEN YEAR(vr.created_at) = YEAR(CURDATE()) - 3 THEN vdv.cantidad_venta END), 0) AS `$anio_actual_4`,
    COALESCE(SUM(CASE WHEN YEAR(vr.created_at) = YEAR(CURDATE()) - 4 THEN vdv.cantidad_venta END), 0) AS `$anio_actual_5`
FROM 
    (SELECT 1 AS mes UNION ALL SELECT 2 UNION ALL SELECT 3 UNION ALL SELECT 4 
     UNION ALL SELECT 5 UNION ALL SELECT 6 UNION ALL SELECT 7 UNION ALL SELECT 8 
     UNION ALL SELECT 9 UNION ALL SELECT 10 UNION ALL SELECT 11 UNION ALL SELECT 12) AS mes
LEFT JOIN ven__recibos vr 
       ON MONTH(vr.created_at) = mes.mes
      AND vr.created_at >= DATE_SUB(CURDATE(), INTERVAL 5 YEAR)
LEFT JOIN ven__detalle_ventas vdv 
       ON vdv.id_venta = vr.id
      AND vdv.id_producto = 30
GROUP BY mes.mes
ORDER BY mes.mes;
";

$resultado = DB::select($sql);

return $resultado;


    $sql = <<<SQL
SELECT 
    mes.mes,
    COALESCE(SUM(CASE WHEN YEAR(vr.created_at) = YEAR(CURDATE()) THEN vdv.cantidad_venta END), 0) AS `current_year`,
    COALESCE(SUM(CASE WHEN YEAR(vr.created_at) = YEAR(CURDATE()) - 1 THEN vdv.cantidad_venta END), 0) AS `year_minus_1`,
    COALESCE(SUM(CASE WHEN YEAR(vr.created_at) = YEAR(CURDATE()) - 2 THEN vdv.cantidad_venta END), 0) AS `year_minus_2`,
    COALESCE(SUM(CASE WHEN YEAR(vr.created_at) = YEAR(CURDATE()) - 3 THEN vdv.cantidad_venta END), 0) AS `year_minus_3`,
    COALESCE(SUM(CASE WHEN YEAR(vr.created_at) = YEAR(CURDATE()) - 4 THEN vdv.cantidad_venta END), 0) AS `year_minus_4`
FROM 
    (SELECT 1 AS mes UNION ALL SELECT 2 UNION ALL SELECT 3 UNION ALL SELECT 4 
     UNION ALL SELECT 5 UNION ALL SELECT 6 UNION ALL SELECT 7 UNION ALL SELECT 8 
     UNION ALL SELECT 9 UNION ALL SELECT 10 UNION ALL SELECT 11 UNION ALL SELECT 12) AS mes
LEFT JOIN ven__detalle_ventas vdv
    JOIN ven__recibos vr ON vdv.id_venta = vr.id
    ON MONTH(vr.created_at) = mes.mes
    AND vr.created_at >= DATE_SUB(CURDATE(), INTERVAL 5 YEAR)
    AND vdv.id_producto = ?
GROUP BY mes.mes
ORDER BY mes.mes;
SQL;

    $rows = DB::select($sql, [$id_producto]);

    return collect($rows);
}


}