<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => 'Administrador',
            'email' => 'admin@ceop.com',
            'password' => bcrypt('password'),
            'role' => 'admin',
            'ativo' => true,
        ]);

        User::create([
            'name' => 'Dr. João Silva',
            'email' => 'joao@ceop.com',
            'password' => bcrypt('password'),
            'role' => 'dentista',
            'cro' => 'CRO-GO 12345',
            'ativo' => true,
        ]);

        User::create([
            'name' => 'Maria Recepcionista',
            'email' => 'maria@ceop.com',
            'password' => bcrypt('password'),
            'role' => 'recepcionista',
            'ativo' => true,
        ]);
    }
}