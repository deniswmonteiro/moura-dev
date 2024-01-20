<?php
//Definindo o namespace
namespace App\Fachada;

//Definindo as classes usadas

use App\Conexao\BDR\Conexao;
use App\Fabrica\Fabrica;
use Exception;

/**
 * Fachada do projeto
 * Todas as chamadas irão passar por esta classe
 *
 * @author Lucas Dantas <lucas@moveon.dev>
 * @package App
 * @subpackage Fachada
 * @version 1.0
 * @since 1.0 28/10/2023 01:03
 */
Class Fachada{

	/**
	 * Instância
	 *
	 * @access protected
	 * @static
	 * @var array
	 */
	protected static $instancia	= [];

	/**
	 * Método construtor da classe
	 *
	 * @access public
	 * @param ?string $modelo Modelo
	 * @param ?object $controlador Controlador
	 * @uses Fabrica::getTipo Obtendo o tipo
	 */
	public function __construct( ?string $modelo, protected ?object $controlador = null ){

		//Definindo o controlador
		$this->controlador	= Fabrica::getTipo( $modelo );

	}

    /**
	 * Obtendo a instância da fachada
	 *
	 * @access public
	 * @static
	 * @param ?string $modelo Modelo
	 * @return Fachada
	 */
	public static function getInstancia( ?string $modelo = null ): Fachada{

		//Verificando
		if( !array_key_exists( $modelo, self::$instancia ) )
			//Definindo
			self::$instancia[ $modelo ]	= new self( $modelo );

		//Retornando
		return self::$instancia[ $modelo ];

	}

	/*
	 * INSERÇÕES
	 */

	/**
	 * Inserindo o conteúdo
	 *
	 * @access public
	 * @param array $fields Fields
	 * @uses App\Conexao\BDR\Conexao->beginTransaction Iniciando a transação
	 * @uses App\Controlador\Controlador->inserir Inserindo o conteúdo
	 * @uses App\Conexao\BDR\Conexao->commit Comitando a transação
	 * @uses App\Conexao\BDR\Conexao->rollback Revertendo a transação
	 * @throws Exception
	 * @return void
	 */
	public function inserir( array $fields = [] ): void{

		try{

			//Definindo a conexão
			$conexao	= Conexao::getInstancia()->conexao;

			//Iniciando a transação
			$conexao->beginTransaction();

			//Inserindo
			$this->controlador->inserir( $fields );

			//Comitando
			$conexao->commit();

		}catch( Exception $e ){

			//Revertendo a transação
			$conexao->rollback();

			//Lançando a exceção
			throw $e;

		}

	}

	/*
	 * EDIÇÕES
	 */

	/**
	 * Editando os cursos em favoritos
	 *
	 * @access public
	 * @param int $id Identificador
	 * @param bool $status Status
	 * @uses App\Conexao\BDR\Conexao->beginTransaction Iniciando a transação
	 * @uses App\Controlador\Controlador->editarFavorito Editando os cursos em favoritos
	 * @uses App\Conexao\BDR\Conexao->commit Comitando a transação
	 * @uses App\Conexao\BDR\Conexao->rollback Revertendo a transação
	 * @return void
	 * @throws Exception
	 */
	public function editarFavorito( int $id, bool $status ): void{

		try{

			//Definindo a conexão
			$conexao	= Conexao::getInstancia()->conexao;

			//Iniciando a transação
			$conexao->beginTransaction();

			//Editando
			$this->controlador->editarFavorito( $id, $status );

			//Comitando
			$conexao->commit();

		}catch( Exception $e ){

			//Revertendo a transação
			$conexao->rollback();

			//Lançando a exceção
			throw $e;

		}

	}

	/*
	 * PROCURAS
	 */

	/**
	 * Procurando o conteúdo pelo seu identificador
	 *
	 * @access public
	 * @param int $id Identificador
	 * @uses App\Controlador\Controlador->procurarPorIdentificador Procurando o conteúdo pelo seu identificador
	 * @return object
	 */
	public function procurarPorIdentificador( int $id = 1 ): object{

		//Procurando e retornando
		return $this->controlador->procurarPorIdentificador( $id );

	}

	/**
	 * Procurando o conteúdo pelo seu slug
	 *
	 * @access public
	 * @param ?string $slug Slug
	 * @uses App\Controlador\Controlador->procurarPorSlug Procurando o conteúdo pelo seu slug
	 * @return object
	 */
	public function procurarPorSlug( ?string $slug = null ): object{

		//Procurando e retornando
		return $this->controlador->procurarPorSlug( $slug );

	}

	/**
	 * Procurando o conteúdo por status e pelo seu slug
	 *
	 * @access public
	 * @param int $status Status
	 * @param ?string $slug Slug
	 * @uses App\Controlador\Controlador->procurarPorStatusPorSlug Procurando o conteúdo por status e pelo seu slug
	 * @return object
	 */
	public function procurarPorStatusPorSlug( int $status = STATUS_ATIVO, ?string $slug = null ): object{

		//Procurando e retornando
		return $this->controlador->procurarPorStatusPorSlug( $status, $slug );

	}

	/**
	 * Procurando o conteúdo pelo seu documento
	 *
	 * @access public
	 * @param ?string $documento Documento
	 * @param bool $exception Showing or not the exception
	 * @uses App\Controlador\Controlador->procurarPorDocumento Procurando o conteúdo pelo seu documento
	 * @return mixed
	 */
	public function procurarPorDocumento( string $documento = null ): mixed{

		//Retornando
		return $this->controlador->procurarPorDocumento( $documento );

	}


	/*
	 * LISTAGENS
	 */

	/**
	 * Listando por status, de forma ordenada e por quantidade
	 *
	 * @access public
	 * @param int $status Status
	 * @param ?string $ordem Ordem de aparição dos conteúdos
	 * @param int $quantidade Quantidade de conteúdos
	 * @uses App\Controlador\Controlador->listarPorStatusOrdenadoPorQuantidade Listando por status, de forma ordenada e por quantidade
	 * @return mixed
	 */
	public function listarPorStatusOrdenadoPorQuantidade( int $status = 1, ?string $ordem = null, int $quantidade = 10 ): mixed{

		//Retornando
		return $this->controlador->listarPorStatusOrdenadoPorQuantidade( $status, $ordem, $quantidade );

	}

	/**
	 * Listando por status e de forma ordenada
	 *
	 * @access public
	 * @param int $status Status
	 * @param ?string $ordem Ordem de aparição dos conteúdos
	 * @uses App\Controlador\Controlador->listarPorStatusOrdenado Listando por status e de forma ordenada
	 * @return mixed
	 */
	public function listarPorStatusOrdenado( int $status = STATUS_ATIVO, ?string $ordem = null ): mixed{

		//Retornando
		return $this->controlador->listarPorStatusOrdenado( $status, $ordem );

	}

	/**
	 * Listando de forma ordenada e por quantidade
	 *
	 * @access public
	 * @param ?string $ordem Ordem de aparição dos conteúdos
	 * @param int $quantidade Quantidade de conteúdos
	 * @uses App\Controlador\Controlador->listarOrdenadoPorQuantidade Listando de forma ordenada e por quantidade
	 * @return mixed
	 */
	public function listarOrdenadoPorQuantidade( ?string $ordem = null, int $quantidade = 10 ): mixed{

		//Retornando
		return $this->controlador->listarOrdenadoPorQuantidade( $ordem, $quantidade );

	}

	/**
	 * Listando de forma ordenada
	 *
	 * @access public
	 * @param ?string $ordem Ordem de aparição dos conteúdos
	 * @uses App\Controlador\Controlador->listarOrdenado Listando de forma ordenada
	 * @return mixed
	 */
	public function listarOrdenado( ?string $ordem = null ): mixed{

		//Retornando
		return $this->controlador->listarOrdenado( $ordem );

	}

	/**
	 * Listando por estado e por cidade
	 *
	 * @access public
	 * @param ?string $estado Estado
     * @param ?string $cidade Cidade
	 * @uses App\Controlador\Controlador->listarPorEstadoPorCidade Listando por estado e por cidade
	 * @return mixed
	 */
	public function listarPorEstadoPorCidade( ?string $estado = null, ?string $cidade = null ): mixed{

		//Retornando
		return $this->controlador->listarPorEstadoPorCidade( $estado, $cidade );

	}

	/**
	 * Listando os conteúdos por status, favoritados, de forma ordenada e por quantidade
	 *
	 * @access public
	 * @param int $status Status
	 * @param ?string $ordem Ordem de aparição dos conteúdos
	 * @param int $quantidade Quantidade de conteúdos
	 * @uses App\Controlador\Controlador->listarPorStatusMeusFavoritosOrdenadoPorQuantidade Listando os conteúdos por status, favoritados, de forma ordenada e por quantidade
	 * @return mixed
	 */
	public function listarPorStatusMeusFavoritosOrdenadoPorQuantidade( int $status = STATUS_ATIVO, ?string $ordem = null, int $quantidade = 10 ): mixed{

		//Retornando
		return $this->controlador->listarPorStatusMeusFavoritosOrdenadoPorQuantidade( $status, $ordem, $quantidade );

	}

	/**
	 * Listando os conteúdos por status, de forma ordenada e limitada
	 *
	 * @access public
	 * @param int $status Status
	 * @param null|string $ordem Ordem de aparição dos conteúdos
	 * @param int $limitador Limitador de conteúdos por página
	 * @uses App\Controlador\Controlador->listarPorStatusOrdenadoLimitado Listando os conteúdos por status, de forma ordenada e limitada
	 * @return mixed
	 */
	public function listarPorStatusOrdenadoLimitado( int $status = STATUS_ATIVO, ?string $ordem = null, int $limitador = 0 ): mixed{

		//Retornando
		return $this->controlador->listarPorStatusOrdenadoLimitado( $status, $ordem, $limitador );

	}

	/**
	 * Listando os conteúdos por status, por termos, de forma ordenada e limitada
	 *
	 * @access public
	 * @param int $status Status
	 * @param array $termos Termos
	 * @param null|string $ordem Ordem de aparição dos conteúdos
	 * @param int $limitador Limitador de conteúdos por página
	 * @uses App\Controlador\Controlador->listarPorStatusPorTermosOrdenadoLimitado Listando os conteúdos por status, por termos, de forma ordenada e limitada
	 * @return mixed
	 */
	public function listarPorStatusPorTermosOrdenadoLimitado( int $status = STATUS_ATIVO, array $termos = [], ?string $ordem = null, int $limitador = 0 ): mixed{

		//Retornando
		return $this->controlador->listarPorStatusPorTermosOrdenadoLimitado( $status, $termos, $ordem, $limitador );

	}

	/*
	 * CONTAGENS
	 */

	/**
	 * Contando a quantidade de conteúdos por status
	 *
	 * @access public
	 * @param int $status Status
	 * @uses App\Controlador\Controlador->contarPorStatus Contando a quantidade de conteúdos por status
	 * @return int
	 */
	public function contarPorStatus( int $status = STATUS_ATIVO ): int{

		//Retornando
		return $this->controlador->contarPorStatus( $status );

	}

	/**
	 * Contando a quantidade de conteúdos por status e por termos
	 *
	 * @access public
	 * @param int $status Status
	 * @param array $termos Termos
	 * @uses App\Controlador\Controlador->contarPorStatusPorTermos Contando a quantidade de conteúdos por status e por termos
	 * @return int
	 */
	public function contarPorStatusPorTermos( int $status = STATUS_ATIVO, array $termos = [] ): int{

		//Retornando
		return $this->controlador->contarPorStatusPorTermos( $status, $termos );

	}

}