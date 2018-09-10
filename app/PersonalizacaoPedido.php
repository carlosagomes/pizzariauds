<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PersonalizacaoPedido extends Model
{
    public function pedido(){
    	return $this->hasOne('App\Pedido','id','pedido_id');
    }

    public function personalizacao(){
    	return $this->hasOne('App\Personalizacao','id','personalizacao_id');
    }

}
