<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Prod_Producto extends Model
{
    protected $fillable=['idlinea',
                            'codigo',
                            'correlativo',
                            'nombre',
                            'iddispenser',
                            'cantidad_dispenser',
                            'idformafarm',
                            'indicaciones',
                            'dosificacion',
                            'accion_terapeutica',
                            'principio_activo',
                            'imagen',
                            'tiempo_pedido',
                            'precio_lista',
                            'precio_venta',
                            'estado',
                            'activo'
                        ];
}
