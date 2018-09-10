<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

use App\Sabor;

class SaborTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $sabores = [
            ['id' => 1, 'tipo' => 'Calabresa', 'tempo_extra' => 0 , 'status' => true],
            ['id' => 2, 'tipo' => 'Marguerita', 'tempo_extra' => 0 , 'status' => true],
            ['id' => 3, 'tipo' => 'Portuguesa', 'tempo_extra' => 5 , 'status' => true]
        ];

        foreach ($sabores as $sabor) {
            Sabor::create($sabor);
        }
    }
}