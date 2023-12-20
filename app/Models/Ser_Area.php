<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ser_Area extends Model
{
    use HasFactory;
    protected $fillable= ['codigo','correlativo','nonmbre','descripcion'];
}
