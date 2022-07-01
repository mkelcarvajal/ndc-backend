<?php

use Illuminate\Database\Seeder;
use App\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = new User();
        $user->name = 'sebita';
        $user->email = 'seba@hola.xd';
        $user->email_verified_at = now();
        $user->password = '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi';
        $user->rut = '11111111-1';
        $user->direccion = 'Quepasa 123';
        $user->telefono = '955887744';
        $user->tipo_usuario = 'normal';
        $user->remember_token = Str::random(10);
        $user->save();

        $user = new User();
        $user->name = 'cristian';
        $user->email = 'ctorres@hola.xd';
        $user->email_verified_at = now();
        $user->password = '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi';
        $user->rut = '11111111-2';
        $user->direccion = 'Quepasa 123';
        $user->telefono = '955887744';
        $user->tipo_usuario = 'normal';
        $user->remember_token = Str::random(10);
        $user->save();

    }
}
