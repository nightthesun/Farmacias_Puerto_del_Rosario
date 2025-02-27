<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class EjecutarSincronizacion extends Command
{
    protected $signature = 'sincronizacion:auto';
    protected $description = 'Ejecuta la sincronización automática basada en la configuración de la base de datos';
   
    public function handle()
    {
        // Obtener todas las sincronizaciones programadas
        $sincronizacion = DB::table('auto__sincronizacion')->where('id', 1)->where('activo',1)->first();
        Log::info("datos....");

        if (!$sincronizacion) {
            $this->info("La sincronización está desactivada o no existe.");
            return;
        }           
    }
    
    


}
