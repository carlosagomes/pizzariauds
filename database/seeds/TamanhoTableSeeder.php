<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

use App\Tamanho;

class TamanhoTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $tamanhos = [
             ['id' => 1, 'tipo' => 'Pequena', 'tempo_preparo' => 15 , 'valor' => 20.0 , 'status' => true],
             ['id' => 2, 'tipo' => 'Média',   'tempo_preparo' => 20 , 'valor' => 30.0 , 'status' => true],
             ['id' => 3, 'tipo' => 'Grande', 'tempo_preparo' => 25 , 'valor' => 40.0 , 'status' => true]
        ];

        foreach ($tamanhos as $tamanho) {
            Tamanho::create($tamanho);
        }
    }
}