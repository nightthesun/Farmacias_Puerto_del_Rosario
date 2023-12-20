<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Par_DescServicio extends Model
{
    use HasFactory;
    protected $fillable=['nombre','descripcion','siporcentaje','monto'];
}
