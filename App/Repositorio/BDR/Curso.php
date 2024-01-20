<?php
//Definindo o namespace
namespace App\Repositorio\BDR;

//Definindo as classes usadas
use App\Excecao\BancoDeDados as ExcecaoBancoDeDados;
use App\Repositorio\BDR\Repositorio as Principal;
use App\Excecao\Conteudo as ExcecaoConteudo;
use App\Mensagem\Mensagem;
use App\Util\Util;
use PDOException;
use PDO;

/**
 * Repositório do curso
 * Herdada da classe pai Repositorio.
 *
 * @author Lucas Dantas <lucas@moveon.dev>
 * @package Repositorio
 * @subpackage BDR
 * @version 1.0
 * @since 1.0 28/10/2023 20:32
 */
Class Curso Extends Principal{

	/*
	 * INSERÇÕES
	 */

	/**
	 * Inserindo o curso nos favoritos
	 *
	 * @access public
	 * @param int $id Identificador do curso
	 * @return void
	 * @throws ExcecaoBancoDeDados
	 */
	public function inserirFavorito( int $id ): void{

		try{

			//Preparando a consulta
			$pdo    = $this->conexao->prepare( 'INSERT INTO customer_course_favorite VALUES(:customer,:course)' );

			//Definindo os valores
			$pdo->bindValue( ':customer', $_SESSION[ 'cliente-id' ], PDO::PARAM_INT );
			$pdo->bindValue( ':course', $id, PDO::PARAM_INT );

			//Executando
			$pdo->execute();

		}catch( PDOException $e ){

			//Levantando a exceção
			throw new ExcecaoBancoDeDados( Mensagem::INSERIR, $e, resposta: RESPOSTA_SOLICITACAO_INCORRETA );

		}

	}

	/*
	 * REMOÇÕES
	 */

	/**
	 * Removendo o curso dos favoritos
	 *
	 * @access public
	 * @param int $id Identificador do curso
	 * @return void
	 * @throws ExcecaoBancoDeDados
	 */
	public function removerFavorito( int $id ): void{

		try{

			//Preparando a consulta
			$pdo    = $this->conexao->prepare( 'DELETE FROM customer_course_favorite WHERE customer = :customer AND course = :course' );

			//Definindo os valores
			$pdo->bindValue( ':customer', $_SESSION[ 'cliente-id' ], PDO::PARAM_INT );
			$pdo->bindValue( ':course', $id, PDO::PARAM_INT );

			//Executando
			$pdo->execute();

		}catch( PDOException $e ){

			//Levantando a exceção
			throw new ExcecaoBancoDeDados( Mensagem::REMOVER, $e, resposta: RESPOSTA_SOLICITACAO_INCORRETA );

		}

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
     * @return array
	 * @throws Exception
	 */
	public function listarPorTrilhaOrdenado( int $trilha, ?string $ordem ): mixed{

		try{

			//Preparando a consulta
			$pdo    = $this->conexao->prepare( 'SELECT ' . $this->configuracao->campos . ' FROM course C INNER JOIN course_trail CT ON (C.id = CT.course) WHERE CT.trail = :trilha ORDER BY ' . Util::definirOrdemBancoDeDados( $ordem ) );

			//Definindo os valores
			$pdo->bindValue( ':trilha', $trilha, PDO::PARAM_INT );

			//Executando
			$pdo->execute();

			//Definindo os valores
			$conteudos	= $pdo->fetchAll( PDO::FETCH_ASSOC );

			//Verificando a quantidade
			if( count( $conteudos ) > 0 ){

				//Listando
				foreach( $conteudos as $conteudo )
					//Definindo
					$array[]	= (object) ( new $this->modelo( $conteudo, $this->formatar, $this->remover ) )->propriedades;

				//Retornando
				return $array;

			}else
				//Retornando
                return null;

		}catch( PDOException $e ){

			//Levantando a exceção
			throw new ExcecaoBancoDeDados( Mensagem::LISTAR, $e, resposta: RESPOSTA_SOLICITACAO_INCORRETA );

		}

	}

	/**
	 * Listando os conteúdos por status, favoritados e de forma ordenada
	 *
	 * @access public
	 * @param int $status Status
	 * @param ?string $ordem Ordem de aparição dos conteúdos
	 * @param int $quantidade Quantidade de conteúdos
     * @return array
	 * @throws Exception
	 */
	public function listarPorStatusMeusFavoritosOrdenadoPorQuantidade( int $status, ?string $ordem, int $quantidade ): mixed{

		try{

			//Preparando a consulta
			$pdo    = $this->conexao->prepare( 'SELECT ' . $this->configuracao->campos . ' FROM course C INNER JOIN customer_course_favorite CCF ON (C.id = CCF.course) WHERE CCF.customer = :cliente AND C.status = :status ORDER BY ' . Util::definirOrdemBancoDeDados( $ordem ) . ' LIMIT :quantidade' );

			//Definindo os valores
			$pdo->bindValue( ':cliente', $_SESSION[ 'cliente-id' ], PDO::PARAM_INT );
			$pdo->bindValue( ':quantidade', $quantidade, PDO::PARAM_INT );
			$pdo->bindValue( ':status', $status, PDO::PARAM_INT );

			//Executando
			$pdo->execute();

			//Definindo os valores
			$conteudos	= $pdo->fetchAll( PDO::FETCH_ASSOC );

			//Verificando a quantidade
			if( count( $conteudos ) > 0 ){

				//Listando
				foreach( $conteudos as $conteudo )
					//Definindo
					$array[]	= (object) ( new $this->modelo( $conteudo, $this->formatar, $this->remover ) )->propriedades;

				//Retornando
				return $array;

			//Exibindo ou não a exceção
			}else if( $this->excecao )
				//Lançando a exceção
				throw new ExcecaoConteudo( sprintf( Mensagem::NENHUM_REGISTRO_ENCONTRADO, $this->configuracao->nome ), RESPOSTA_NAO_ENCONTRADO );
			else
				//Retornando
                return null;

		}catch( PDOException $e ){

			//Levantando a exceção
			throw new ExcecaoBancoDeDados( Mensagem::LISTAR, $e, resposta: RESPOSTA_SOLICITACAO_INCORRETA );

		}

	}

	/**
	 * Listando os conteúdos por categoria, de forma ordenada e por quantidade
	 *
	 * @access public
	 * @param int $categoria Categoria
	 * @param ?string $ordem Ordem de aparição dos conteúdos
	 * @param int $quantidade Quantidade de conteúdos
	 * @param ?string $complemento Complamento que virá de outros métodos
	 * @param array $parametros Parâmetros que virão de outros métodos
     * @uses $this->listarPorCategoriaOrdenado Listando os conteúdos por categoria e de forma ordenada
     * @return mixed
	 */
	public function listarPorCategoriaOrdenadoPorQuantidade( int $categoria, ?string $ordem, int $quantidade, ?string $complemento = null, array $parametros = [] ):mixed {

		//Definindo o complemento
		$complemento	= 'LIMIT :quantidade ' . $complemento;

		//Definindo os parâmetros
		$parametros		= [ ':quantidade' => [ 'valor' => $quantidade, 'tipo' => PDO::PARAM_INT ], ...$parametros ];

		//Retornando
		return $this->listarPorCategoriaOrdenado( $categoria, $ordem, $complemento, $parametros );

	}

	/**
	 * Listando os conteúdos por categoria e de forma ordenada
	 *
	 * @access public
	 * @param int $categoria Categoria
	 * @param ?string $ordem Ordem de aparição dos conteúdos
	 * @param ?string $complemento Complamento que virá de outros métodos
	 * @param array $parametros Parâmetros que virão de outros métodos
     * @uses App\Util\Util::definirOrdemBancoDeDados Definindo a ordem dos conteúdos para o banco de dados
     * @uses $this->listarPorCategoria Listando os conteúdos por categoria
     * @return mixed
	 */
	public function listarPorCategoriaOrdenado( int $categoria, ?string $ordem, ?string $complemento = null, array $parametros = [] ):mixed {

		//Definindo o complemento
		$complemento	= 'ORDER BY ' . Util::definirOrdemBancoDeDados( $ordem ) . ' ' . $complemento;

		//Retornando
		return $this->listarPorCategoria( $categoria, $complemento, $parametros );

	}

	/**
	 * Listando os conteúdos por categoria
	 *
	 * @access public
	 * @param int $categoria Categoria
	 * @param ?string $complemento Complamento que virá de outros métodos
	 * @param array $parametros Parâmetros que virão de outros métodos
     * @uses $this->listar Listando os conteúdos
     * @return mixed
	 */
	public function listarPorCategoria( int $categoria, ?string $complemento = null, array $parametros = [] ):mixed {

		//Definindo o complemento
		$complemento	= 'AND category = :categoria ' . $complemento;

		//Definindo os parâmetros
		$parametros		= [ ':categoria' => [ 'valor' => $categoria, 'tipo' => PDO::PARAM_INT ], ...$parametros ];

		//Retornando
		return $this->listar( $complemento, $parametros );

	}

}