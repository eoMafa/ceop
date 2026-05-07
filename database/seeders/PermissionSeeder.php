<?php

namespace Database\Seeders;

use App\Models\Permission;
use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    public function run(): void
    {
        $modulos = Permission::modulos();
        $acoes   = Permission::acoes();

        foreach ($modulos as $modulo => $labelModulo) {
            foreach ($acoes as $acao => $labelAcao) {
                Permission::firstOrCreate(
                    ['modulo' => $modulo, 'acao' => $acao],
                    ['nome' => "{$labelAcao} {$labelModulo}"]
                );
            }
        }
    }
}