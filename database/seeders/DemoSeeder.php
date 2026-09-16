<?php

namespace Database\Seeders;

use App\Models\Empresa;
use App\Models\Usuario;
use Illuminate\Database\Seeder;

class DemoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $usuario = Usuario::factory()
            ->has(Empresa::factory()->completa())
            ->create();
    }
}
