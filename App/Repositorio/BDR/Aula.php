<?php
//Definindo o namespace
namespace App\Repositorio\BDR;

//Definindo as classes usadas
use App\Repositorio\BDR\Repositorio as Principal;
use App\Util\Util;
use PDO;

/**
 * Repositório das aulas do curso
 * Herdada da classe pai Repositorio.
 *
 * @author Lucas Dantas <lucas@moveon.dev>
 * @package Repositorio
 * @subpackage BDR
 * @version 1.0
 * @since 1.0 03/11/2023 21:41
 */
Class AUla Extends Principal{

	/**
	 * Listando os conteúdos por curso e de forma ordenada
	 *
	 * @access public
	 * @param int $curso Curso
	 * @param ?string $ordem Ordem de aparição dos conteúdos
	 * @param ?string $complemento Complamento que virá de outros métodos
	 * @param array $parametros Parâmetros que virão de outros métodos
     * @uses App\Util\Util::definirOrdemBancoDeDados Definindo a ordem dos conteúdos para o banco de dados
     * @uses $this->listarPorCurso Listando os conteúdos por curso
     * @return mixed
	 */
	public function listarPorCursoOrdenado( int $curso, ?string $ordem, ?string $complemento = null, array $parametros = [] ):mixed {

		//Definindo o complemento
		$complemento	= 'ORDER BY ' . Util::definirOrdemBancoDeDados( $ordem ) . ' ' . $complemento;

		//Definindo e retornando os conteúdos
		return $this->listarPorCurso( $curso, $complemento, $parametros );

	}

	/**
	 * Listando os conteúdos por curso
	 *
	 * @access public
	 * @param int $curso Curso
	 * @param ?string $complemento Complamento que virá de outros métodos
	 * @param array $parametros Parâmetros que virão de outros métodos
     * @uses $this->listar Listando os conteúdos
     * @return mixed
	 */
	public function listarPorCurso( int $curso, ?string $complemento = null, array $parametros = [] ):mixed {

		//Definindo o complemento
		$complemento	= 'AND course = :curso ' . $complemento;

		//Definindo os parâmetros
		$parametros		= [ ':curso' => [ 'valor' => $curso, 'tipo' => PDO::PARAM_INT ], ...$parametros ];

		//Definindo e retornando os conteúdos
		return $this->listar( $complemento, $parametros );

	}

}