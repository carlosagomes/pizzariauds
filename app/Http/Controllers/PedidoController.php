<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Sabor;
use App\Tamanho;
use App\Personalizacao;
use App\Pedido;
use App\PersonalizacaoPedido;

class PedidoController extends Controller
{
    /**
     * Retorna todos os sabores com status ativo
     *
     * @return sabores
     */
    public function getSabores(){
    	$dados = Sabor::where('status',1)->get();
    	return response()->json($dados);
    }

    /**
     * Retorna todos os tamanhos com status ativo
     *
     * @return tamanhos
     */
    public function getTamanhos(){
    	$dados = Tamanho::where('status',1)->get();
    	return response()->json($dados);
    }

    /**
     * Retorna todos os getPersonalizcao com status ativo
     *
     * @return getPersonalizcao
     */
    public function getPersonalizacao(){
    	$dados = Personalizacao::where('status',1)->get();
    	return response()->json($dados);
    }

    /**
     * Retorna o pedido montado com sabe nas informacoes recebidas
     *
     * @return pedido
     */
    public function montarPedido(Request $request){
    	// INICIALIZA AS VARIAVEIS DE RETORNO
    	$status 				= 'success';
    	$msg 					= 'Pedido realizado com Sucesso!';


    	// BUSCA OS DADOS DE TAMANHO
    	$dadosTamanho 			= Tamanho::where('id',$request['tamanho_id'])->first();
    	// BUSCA OS DADOS DE SABOR
    	$dadosSabor   			= Sabor::where('id',$request['sabor_id'])->first();
        

        $array_personalizado = '';
    	// BUSCA OS DADOS DE PERSONALIZACAO
        if(!empty($request['personalizacoes_ids'])){
            $array_personalizado    = explode(',', $request['personalizacoes_ids']);
            $dadosPersonalizacao    = Personalizacao::whereIn('id',$array_personalizado)->get();
          
        }

    	if(!empty($dadosTamanho) && !empty($dadosSabor)){

      
    		// INICIALIZA AS VARIAVEIS DE PERSONALIZACAO 
    		$personalizacao_tempo_preparo 	= 0;
    		$personalizacao_valor 			= 0;

    		// RECEBE O TAMANHO DA PIZZA
    		$pedido['tamanho'] 				= $dadosTamanho->tipo;
    		$pedido['tamanho_valor'] 		= $dadosTamanho->valor;
    		// RECEBE O SABOR DA PIZZA
    		$pedido['sabor'] 				= $dadosSabor->tipo;
    		$pedido['personalizacoes'] 		= '';
        
    		// CASO TENHA PERSONALIZACAO VAI ENTRAR NO SEGUINTE IF
    		if(!empty($dadosPersonalizacao)){
                $personalizacoes = [];
    			foreach ($dadosPersonalizacao as $chave => $personalizacao) {
    				$personalizacoes[$chave]['tipo'] 		= $personalizacao->tipo;
    				$personalizacoes[$chave]['valor'] 	    = $personalizacao->valor;
    				// GRAVA OS VALORES DE TEMPO E VALOR JA INICIALIZADO COM 0
    				$personalizacao_tempo_preparo 					+= $personalizacao->tempo_extra; 
    				$personalizacao_valor 							+= $personalizacao->valor; 
    			}

                $pedido['personalizacoes']      = $personalizacoes;
    		}

        

    		// SOMA OS VALOR DE TAMANHO + PERSONALIZACAO
            $valor_total                     = $dadosTamanho->valor + $personalizacao_valor ;
    		$pedido['valor_total'] 			 = $valor_total;
    		// CALCULA O TEMPO DE PREPARO DE TAMANHO + SABOR + PERSONALIZACAO
            $tempo_preparo                   =  $dadosTamanho->tempo_preparo + $dadosSabor->tempo_extra + $personalizacao_tempo_preparo ;
    		$pedido['tempo_preparo']		 =  $tempo_preparo ;
            
            // CHAMA A FUNCAO RESPONSAVEL POR SALVAR O PEDIDO NO BANCO DE DADOS
            $pedido['pedido_id']             = $this->salvarPedido($request['tamanho_id'],$request['sabor_id'],$valor_total,$tempo_preparo,$array_personalizado);

            // CONVERTE OS DADOS EM JSON PARA RETORNAR 
    		$dados = ($pedido);
    	}else{
    		// CASO ALGO DE ERRADO RETORNA O SEGUINTE MSG 
	    	$status 				= 'error';
	    	$msg 					= 'Houve algo errado na montagem de seu Pedido, favor verificar!'; 
	    	$dados 					= '';   		
    	}

        $response = array(
            'status'    => $status,
            'msg'       => $msg,
            'dados'     => $dados,
        );

    	return response()->json($response,200);
    }

    /**
     * vai salvar o pedido
     *
     * @return pedido_id
     */
    public function salvarPedido($tamanho_id,$sabor_id,$valor_total,$tempo_preparo,$personalizacoes_ids = null){
        $pedido                         = new Pedido();
        $pedido->tamanho_id             = $tamanho_id;
        $pedido->sabor_id               = $sabor_id;
        $pedido->valor_total            = $valor_total;
        $pedido->tempo_total_preparo    = $tempo_preparo;
        $pedido->save();
        $pedido_id                      = $pedido->id;

 
        if(!empty($personalizacoes_ids)){
            foreach ($personalizacoes_ids as $chave => $id) {
                $personalizacaoPedido                       = new PersonalizacaoPedido();
                $personalizacaoPedido->pedido_id            = $pedido_id;
                $personalizacaoPedido->personalizacao_id    = $id;
                $personalizacaoPedido->save();
            }
        }
        return $pedido_id;
    }


    /**
     * Retorna todos os sabores com status ativo
     *
     * @return sabores
     */
    public function getPedidoMontadoPorId($id){
        $pedido = Pedido::where('id',$id)->first();

        // BUSCA OS DADOS DE TAMANHO
        $dadosTamanho           = Tamanho::where('id',$pedido->tamanho_id)->first();
        // BUSCA OS DADOS DE SABOR
        $dadosSabor             = Sabor::where('id',$pedido->sabor_id)->first();
        // BUSCA OS DADOS DE PERSONALIZACAO
        $personalizacaoPedido    = PersonalizacaoPedido::where('pedido_id',$id)->get();


        // INICIALIZA AS VARIAVEIS DE PERSONALIZACAO 
        $personalizacao_tempo_preparo   = 0;
        $personalizacao_valor           = 0;

        $pedido['pedido_id']            = $id;

        // RECEBE O TAMANHO DA PIZZA
        $pedido['tamanho']              = $dadosTamanho->tipo;
        $pedido['tamanho_valor']        = $dadosTamanho->valor;

        // RECEBE O SABOR DA PIZZA
        $pedido['sabor']                = $dadosSabor->tipo;
        $pedido['personalizacoes']      = '';

        // CASO TENHA PERSONALIZACAO VAI ENTRAR NO SEGUINTE IF
        if(!empty($personalizacaoPedido)){
            $personalizacoes = [];
            foreach ($personalizacaoPedido as $chave => $perP) {
                $personalizacoes[$chave]['tipo']      = $perP->personalizacao->tipo;
                $personalizacoes[$chave]['valor']     = $perP->personalizacao->valor;
                // GRAVA OS VALORES DE TEMPO E VALOR JA INICIALIZADO COM 0
                $personalizacao_tempo_preparo                   += $perP->personalizacao->tempo_extra; 
                $personalizacao_valor                           += $perP->personalizacao->valor; 
            }
            $pedido['personalizacoes'] = $personalizacoes;
        }

        // SOMA OS VALOR DE TAMANHO + PERSONALIZACAO
        $valor_total                     = $dadosTamanho->valor + $personalizacao_valor ;
        $pedido['valor_total']           = $valor_total;
        // CALCULA O TEMPO DE PREPARO DE TAMANHO + SABOR + PERSONALIZACAO
        $tempo_preparo                   =  $dadosTamanho->tempo_preparo + $dadosSabor->tempo_extra + $personalizacao_tempo_preparo ;
        $pedido['tempo_preparo']         =  $tempo_preparo ;

        $dados = ($pedido);
        $response = array(
            'status'    => 'success',
            'msg'       => 'Dados do Pedido'.$id,
            'dados'     => $dados,
        );        
        return response()->json($dados);
    }
}