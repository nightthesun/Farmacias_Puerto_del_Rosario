<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rrh_Empleado extends Model
{
    use HasFactory;
    protected $fillable=['nombre',
    'papellido',
    'sapellido',
    'sexo',
    'ci',
    'complementoci',
    'iddepartamento',
    'fechanacimiento',
    'foto',
    'estadocivil',
    'idnacionalidad',
    
    'domicilio',
    'idciudad',
    'telefonos',
    'celular',

    'idformacion',
    'idprofesion',
    'idcargo',
    'nit',
    'fechaingreso',
    'fecharetiro',
    
    'idbanco',
    'nrcuenta',
    
    'obs'];
}
