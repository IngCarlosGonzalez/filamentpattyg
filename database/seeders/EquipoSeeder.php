<?php

namespace Database\Seeders;

use App\Models\Equipo;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class EquipoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Equipo::create(
            [
                'name' => 'GENERAL',
                'texto' => 'DEFAULT',
            ]
        );
    }
}
