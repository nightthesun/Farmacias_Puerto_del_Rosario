<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;


class MailConfigServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        if (!Schema::hasTable('adm__credencial_erp')) {
            return;
        }

        $correo = DB::table('adm__credencial_erp')
            ->where('id', 1)
            ->first();

        if (!$correo) {
            return;
        }
$cadena_pass = $correo->mail_password;

$textoEncriptado = substr($cadena_pass, 2, -3);

$password = Crypt::decrypt($textoEncriptado);

        Config::set('mail.default', $correo->mail_mailer);

        Config::set('mail.mailers.smtp.host', $correo->mail_host);
        Config::set('mail.mailers.smtp.port', $correo->mail_port);
        Config::set('mail.mailers.smtp.username', $correo->mail_username);
        Config::set('mail.mailers.smtp.password', $password);
        Config::set('mail.mailers.smtp.encryption', $correo->mail_encryp);

        Config::set('mail.from.address', $correo->mail_from_add);
        Config::set('mail.from.name', $correo->mail_from_na);
    }
}
