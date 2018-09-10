<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

use App\Personalizacao;

class PersonalizacaoTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $states = [
            ['id' => 1, 'tipo' => 'Extra Bacon',    'tempo_extra' => 0 , 'valor' => 3.0 ,   'status' => true], 
            ['id' => 2, 'tipo' => 'Sem Cebola',     'tempo_extra' => 0 , 'valor' => 0 ,     'status' => true],         
            ['id' => 3, 'tipo' => 'Borda Recheada', 'tempo_extra' => 5 , 'valor' => 5.0 ,   'status' => true] 
        ];

        foreach ($states as $state) {
            Personalizacao::create($state);
        }
    }
}