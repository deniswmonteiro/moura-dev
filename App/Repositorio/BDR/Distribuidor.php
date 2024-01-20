<?php
//Definindo o namespace
namespace App\Repositorio\BDR;

//Definindo as classes usadas
use App\Repositorio\BDR\Repositorio as Principal;
use PDO;

/**
 * Repositório do distribuidor
 * Herdada da classe pai Repositorio.
 *
 * @author Lucas Dantas <lucas@moveon.dev>
 * @package Repositorio
 * @subpackage BDR
 * @version 1.0
 * @since 1.0 28/10/2023 20:32
 */
Class Distribuidor Extends Principal{

    /*
     * LISTAGENS
     */

	/**
     * Listando os conteúdos por estado e por cidade
	 *
	 * @access public
	 * @param string $estado Estado
     * @param string $cidade Cidade
	 * @param ?string $complemento Complamento que virá de outros métodos
	 * @param array $parametros Parâmetros que virão de outros métodos
     * @uses $this->listarPorEstado Listando por estado
     * @return mixed
     */
    public function listarPorEstadoPorCidade( string $estado, string $cidade, ?string $complemento = null, array $parametros = [] ):mixed {

		//Definindo o complemento
		$complemento	= 'AND city = :cidade ' . $complemento;

		//Definindo os parâmetros
		$parametros		= [ ':cidade' => [ 'valor' => $cidade, 'tipo' => PDO::PARAM_STR ], ...$parametros ];

		//Retornando
		return $this->listarPorEstado( $estado, $complemento, $parametros );

	}

	/**
     * Listando os conteúdos por estado
	 *
	 * @access public
     * @param string $estado Estado
	 * @param ?string $complemento Complamento que virá de outros métodos
	 * @param array $parametros Parâmetros que virão de outros métodos
     * @uses $this->listar Listando
     * @return mixed
     */
    public function listarPorEstado( string $estado, ?string $complemento = null, array $parametros = [] ):mixed {

		//Definindo o complemento
		$complemento	= 'AND state = :estado ' . $complemento;

		//Definindo os parâmetros
		$parametros		= [ ':estado' => [ 'valor' => $estado, 'tipo' => PDO::PARAM_STR ], ...$parametros ];

		//Retornando
		return $this->listar( $complemento, $parametros );

	}

}