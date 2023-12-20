<?php

namespace Database\Seeders;

use App\Models\Rrh_Empleado;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $empleado=Rrh_Empleado::where('codempleado','ADMIN')->first();
        DB::table('users')->insert(['name'=>'admin','idempleado'=>$empleado->id,'email'=>'admin@admin.com','password'=>bcrypt('secret')]);
    }
}
