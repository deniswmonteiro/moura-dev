<?php
//Definindo o namespace
namespace App\Excecao;

//Definindo as classes usadas
use Exception;

/**
 * Exceção dos conteúdos
 * Herdada da classe pai Exception
 *
 * @author Lucas Dantas <lucas@moveon.dev>
 * @package App
 * @subpackage Excecao
 * @version 1.0
 * @since 1.0 27/10/2023 17:10
 */
Class Conteudo Extends Exception{

	/**
	 * Construtor da classe
	 *
	 * @access public
	 * @param string $mensagem Mensagem
	 * @param int $resposta Código da resposta
	 */
	public function __construct( string $mensagem = null, public int $resposta = RESPOSTA_OK ){

		//Definindo a resposta
		http_response_code( $resposta );

		//Construtor da classe pai
		parent::__construct( $mensagem, $resposta );

	}

}