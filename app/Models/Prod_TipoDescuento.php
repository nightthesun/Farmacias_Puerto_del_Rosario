<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Prod_TipoDescuento extends Model
{
    protected $fillable=['aplica_a','cod','descripcion'];
}
