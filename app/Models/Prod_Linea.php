<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Prod_Linea extends Model
{
    protected $fillable=['codigo','correlativo','nombre','descripcion','tiempo_demora','estado','usuario_registro','usuario_modifica'];
}
