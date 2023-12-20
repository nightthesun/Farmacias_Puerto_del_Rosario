<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ser_Prestacion extends Model
{
    use HasFactory;
    protected $fillable=['nombre','precio','descripcion','codigo','correlativo'];
}
