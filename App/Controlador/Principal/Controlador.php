<?php
//Definindo o namespace
namespace App\Controlador\Principal;

//Definindo as classes usadas
use App\Controlador\Controlador as Principal;
use App\Excecao\Conteudo as ExcecaoConteudo;
use App\Mensagem\Mensagem;

/**
 * Classe do controlador Principal
 * Herdada da classe pai Controlador. Classe usada em caso da não existência do controlador do modelo.
 *
 * @author Lucas Dantas <lucas@moveon.dev>
 * @package Controlador
 * @subpackage Principal
 * @version 1.0
 * @since 1.0 27/10/2023 23:06
 */
Class Controlador Extends Principal{

	/*
	 * VERIFICAÇÕES
	 */

    /**
	 * Verificando a existência do conteúdo por documento
	 *
	 * @access public
	 * @param string $documento Documento
	 * @uses App\Repositorio\Repositorio->verificarExistenciaPorDocumento Verificando a existência do conteúdo por documento
	 * @return bool
	 */
	public function verificarExistenciaPorDocumento( string $documento ): bool{

		//Retornando
		return $this->repositorio->verificarExistenciaPorDocumento( $documento );

	}

	/*
	 * PROCURAS
	 */

	 /**
	 * Procurando o conteúdo pelo seu documento
	 *
	 * @access public
	 * @param string $documento Documento
	 * @uses $this->getComplemento Obtendo o complemento ou não
	 * @uses App\Repositorio\Repositorio->procurarPorDocumento Procurando o conteúdo pelo seu documento
	 * @throws ExcecaoConteudo
	 * @return mixed
	 */
	public function procurarPorDocumento( string $documento ): mixed{

		//Verificando
		if( $this->verificarExistenciaPorDocumento( $documento ) )
			//Retornando
			return $this->getComplemento( $this->repositorio->procurarPorDocumento( $documento ), true );
		else
			//Lançando a exceção
			throw new ExcecaoConteudo( sprintf( Mensagem::NAO_EXISTE_POR_DOCUMENTO, $documento ) );

	}

	/*
	 * LISTAGENS
	 */

	/**
	 * Listando os conteúdos por trilha e de forma ordenada
	 *
	 * @access public
	 * @param int $trilha Identificador da trilha
	 * @param ?string $ordem Ordem de aparição dos conteúdos
	 * @param bool $complemento Obtendo o complemento ou não
	 * @uses App\Repositorio\Repositorio->listarPorTrilhaOrdenado Listando os conteúdos por trilha e de forma ordenada
	 * @uses $this->getComplemento Obtendo o complemento ou não
	 * @return mixed
	 */
	public function listarPorTrilhaOrdenado( int $trilha, ?string $ordem = null, bool $complemento = true ): mixed{

		//Retornando
		return $this->getComplemento( $this->repositorio->listarPorTrilhaOrdenado( $trilha, $ordem ), $complemento );

	}

}