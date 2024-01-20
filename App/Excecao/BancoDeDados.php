<?php
//Definindo o namespace
namespace App\Excecao;

//Definindo as classes usadas
use App\Util\Util;
use PDOException;
use Exception;

/**
 * Classe de exceção para o banco de dados
 * Herdada da classe pai Exception
 *
 * @author Lucas Dantas <lucas@moveon.dev>
 * @package App
 * @subpackage Excecao
 * @version 1.0
 * @since 1.0 28/10/2023 00:37
 */
Class BancoDeDados Extends Exception{

	/**
	 * Método construtor da classe
	 *
	 * @access public
	 * @param string $mensagem Mensagem
	 * @param PDOException $excecao Exceção do repositório
	 * @param string $complemento Complemento da mensagem
	 * @param int $resposta Código da resposta
	 * @uses Util::verificarTipoErroBandoDeDados Verificando o tipo de erro do banco de dados
	 */
	public function __construct( string $mensagem, PDOException $excecao, string $complemento = null, public int $resposta = RESPOSTA_OK ){

		//Definindo o código de resposta
		http_response_code( $resposta );

		//Definindo o complemento da mensagem
		$complemento = Util::verificarTipoErroBandoDeDados( $excecao->errorInfo[ 1 ] );

		//Definindo a mensagem
		$mensagem    = ( !PRODUCAO ) ? $mensagem . ' ' . $complemento . '(' . $excecao->getMessage() . ')' : $mensagem . $complemento;

        //Construtor da classe pai
		parent::__construct( $mensagem, $resposta );

	}

}