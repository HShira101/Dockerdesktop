<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // {-- ← firstOrCreate evita duplicados si el seeder se ejecuta más de una vez --}
        User::firstOrCreate(
            ['correo' => env('SEED_CORREO')],
            [
                'nombre'   => env('SEED_NOMBRE'),
                'alias'    => env('SEED_ALIAS'),
                'password' => bcrypt(env('SEED_PASSWORD')),
            ]
        );
    }
}
