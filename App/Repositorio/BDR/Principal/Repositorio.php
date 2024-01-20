<?php
//Definindo o namespace
namespace App\Repositorio\BDR\Principal;

//Definindo as classes usadas
use App\Repositorio\BDR\Repositorio as Principal;
use PDO;

/**
 * Repositório
 * Herdada da classe pai Repositorio. Classe usada em caso da não existência do repositório do modelo.
 *
 * @author Lucas Dantas <lucas@moveon.dev>
 * @package BDR
 * @subpackage Principal
 * @version 1.0
 * @since 1.0 28/10/2023 20:10
 */
Class Repositorio Extends Principal{

	/**
	 * VERIFICAÇÕES
	 */

    /**
	 * Verificando a existência do conteúdo pelo seu documento
	 *
	 * @access public
	 * @param string $documento Documento
	 * @param ?string $complemento Complemento que virá de outros métodos
	 * @param array $parametros Parâmetros que virão de outros métodos
	 * @uses $this->verificarExistencia Verificando a existência do conteúdo
	 * @return bool
	 */
	public function verificarExistenciaPorDocumento( string $documento, ?string $complemento = null, array $parametros = [] ): bool{

		//Definindo o complemento
		$complemento	= 'AND document = :documento ' . $complemento;

		//Definindo os parâmetros
		$parametros		= [ ':documento' => [ 'valor' => $documento, 'tipo' => PDO::PARAM_STR ], ...$parametros ];

		//Retornando
		return $this->verificarExistencia( $complemento, $parametros );

	}

	/*
	 * PROCURAS
	 */

	/**
	 * Procurando o conteúdo pelo seu documento
	 *
	 * @access public
	 * @param string $documento Documento
	 * @param ?string $complemento Complemento que virá de outros métodos
	 * @param array $parametros Parâmetros que virão de outros métodos
	 * @uses $this->procurar Procurando
	 * @return mixed
	 */
	public function procurarPorDocumento( string $documento, ?string $complemento = null, array $parametros = [] ): mixed{

		//Definindo o complemento
		$complemento	= 'AND document = :documento ' . $complemento;

		//Definindo os parâmetros
		$parametros		= [ ':documento' => [ 'valor' => $documento, 'tipo' => PDO::PARAM_STR ], ...$parametros ];

		//Retornando
		return $this->procurar( $complemento, $parametros );

	}

}