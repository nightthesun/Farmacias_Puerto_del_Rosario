<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AdmCredencialSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
    DB::table('adm__credencial_erp')->insert(['mail_mailer'=>'smtp','mail_host'=>'127.0.0.1','mail_port'=>2525,'mail_username'=>null,'mail_password'=>null,'mail_encryp'=>null,'mail_from_add'=>'hello@example.com','mail_from_na'=>'RIPSTER']);
   
    }
}
