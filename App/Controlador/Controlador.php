<?php
//Definindo o namespace
namespace App\Controlador;

//Definindo as classes usadas
use App\Excecao\Conteudo as ExcecaoConteudo;
use App\Validacao\Validacao;
use App\Mensagem\Mensagem;
use App\Fabrica\Fabrica;
use App\Modelo\Modelo;
use App\Util\Util;

/**
 * Classe do controlador
 * Toda regra de negócio do projeto passará por esta classe
 *
 * @abstract
 * @author Lucas Dantas <lucas@moveon.dev>
 * @package App
 * @subpackage Controlador
 * @version 1.0
 * @since 1.0 27/10/2023 21:36
 */
Abstract Class Controlador{

	/**
	 * Método construtor da classe
	 *
	 * @access public
	 * @param object $repositorio repositorio
	 * @param string $modelo Classe modelo
	 */
	public function __construct( protected object $repositorio, public string $modelo ){

		$this->repositorio	= $repositorio;
		$this->modelo		= $modelo;

    }

	/**
	 * Obtendo o controlador
	 *
	 * @access public
	 * @static
	 * @param string $modelo
	 * @uses Fabrica::getTipo Obtendo o tipo(controlador ou repositório)
	 * @return Controlador
	 */
	public static function getControlador( string $modelo ): Controlador{

		//Retornando
		return Fabrica::getTipo( $modelo );

    }

	/**
	 * Métodos extras
	 * Geralmente é chamado por outros métodos para complementar o método
	 *
	 * @access public
	 * @return void
	 */
	public function extra(): void{}

	/**
	 * Obetendo os complementos dos modelos
	 *
	 * @access public
	 * @param mixed $conteudos Conteúdos
	 * @param bool $complemento Obtendo ou não os complementos
	 * @return mixed
	 */
	public function getComplemento( mixed $conteudos, bool $complemento = true ): mixed{

		//Retornando
		return $conteudos;

	}

	/*
	 * VERIFICAÇÕES
	 */

	/**
	 * Verificando os campos únicos
	 * Método que irá verificar ao cadastrar ou editar um conteúdo se o mesmo já existe no repositório
	 *
	 * @access protected
	 * @param Modelo $modelo Modelo
	 * @param array $campos Campos
	 * @param string $metodo Método
	 * @uses Util::definirMetodoUnico Definindo os métodos para checagem única
	 * @return void
	 * @throws ExcecaoConteudo
	 */
	protected function verificarCamposUnicos( Modelo $modelo, array $campos, string $metodo = 'inserir' ): void{

		//Verificando
		if( isset( $modelo->configuracao[ 'unico' ] ) && $modelo->configuracao[ 'unico' ] ){

			//Listando
			foreach( $modelo->configuracao[ 'unico' ] as $campoUnico ){

				//Definindo o objeto
				$objeto	= Util::definirMetodoUnico( $campoUnico );

				//Verificando
				if( $metodo == 'editar' ){

					//Verificando e lançando a exceção
					$campos[ 'antigo-' . $campoUnico ] ?? throw new ExcecaoConteudo( sprintf( Mensagem::FALTANDO_PARAMETROS, 'antigo-' . $campoUnico ), RESPOSTA_SOLICITACAO_INCORRETA );

					//Verificando
					if( $campos[ $campoUnico ] != $campos[ 'antigo-' . $campoUnico ] )
						//Verificando
						if( $this->{$objeto->metodo}( $campos[ $campoUnico ] ) )
							//Lançando a exceção
							throw new ExcecaoConteudo( $objeto->excecao, RESPOSTA_SOLICITACAO_INCORRETA );

				}else if( $metodo == 'inserir' )
					//Verificando
					if( $this->{$objeto->metodo}( $campos[ $campoUnico ] ) )
						//Lançando a exceção
						throw new ExcecaoConteudo( $objeto->excecao, RESPOSTA_SOLICITACAO_INCORRETA );

			}

		}

	}

	/*
     * VERIFICAÇÕES
     */

	/**
	 * Verificando a existência do conteúdo pelo seu identificador
	 *
	 * @access public
	 * @param int $id Identificador
	 * @uses App\Repositorio\Repositorio->verificarExistenciaPorId Verificando a existência do conteúdo pelo seu identificador
	 * @return bool
	 */
	public function verificarExistenciaPorId( int $id ): bool{

		//Verificando e retornando
		return $this->repositorio->verificarExistenciaPorId( $id );

	}

	/**
	 * Verificando a existência do conteúdo pelo seu email
	 *
	 * @access public
	 * @param ?string $email Email
	 * @uses App\Repositorio\Repositorio->verificarExistenciaPorEmail Verificando a existência do conteúdo pelo seu email
	 * @return bool
	 */
	public function verificarExistenciaPorEmail( ?string $email ): bool{

		//Verificando e retornando
		return $this->repositorio->verificarExistenciaPorEmail( $email );

	}

	/**
	 * Verificando a existência do conteúdo por status e pelo seu slug
	 *
	 * @access public
	 * @param int $status Status
	 * @param ?string $slug Slug
	 * @uses repositorio->verificarExistenciaPorStatusPorSlug Verificando a existência do conteúdo por status e pelo seu slug
	 * @return bool
	 */
	public function verificarExistenciaPorStatusPorSlug( int $status, ?string $slug ): bool{

		//Verificando e retornando
		return $this->repositorio->verificarExistenciaPorStatusPorSlug( $status, $slug );

	}

	/**
	 * Verificando a existência do conteúdo pelo seu slug
	 *
	 * @access public
	 * @param ?string $slug Slug
	 * @uses repositorio->verificarExistenciaPorSlug Verificando a existência do conteúdo pelo seu slug
	 * @return bool
	 */
	public function verificarExistenciaPorSlug( ?string $slug ): bool{

		//Verificando e retornando
		return $this->repositorio->verificarExistenciaPorSlug( $slug );

	}

	/*
	 * INSERÇÕES
	 */

	/**
	 * Inserindo o conteúdo no repositório
	 *
	 * @access public
	 * @param array $campos Campos
	 * @uses App\Validacao\Validacao::validarArray Validando o array
	 * @uses $this->verificarCamposUnicos Verificando os campos únicos para a inserção
	 * @uses App\Repositorio\Repositorio->inserir Inserindo o conteúdo no repositório
	 * @return void
	 */
	public function inserir( array $campos ): void{

		//Validando
        Validacao::validarArray( $campos ?? null, 'campos do conteúdo' );

		//Definindo
		$modelo	= new $this->modelo( $campos );

		//Verificando
		$this->verificarCamposUnicos( $modelo, $campos );

		//Inserindo
		$this->repositorio->inserir( $modelo );

	}

	/*
	 * PROCURAS
	 */

	/**
	 * Procurando o conteúdo pelo seu identificador
	 *
	 * @access public
	 * @param int $id Identificador
	 * @param bool $complemento Obtendo o complemento ou não
	 * @uses $this->verificarExistenciaPorId Verificando a existência do conteúdo pelo seu identificador
	 * @uses App\Repositorio\Repositorio->procurarPorIdentificador Procurando o conteúdo pelo seu identificador
	 * @uses $this->getComplemento Obtendo o complemento ou não
	 * @throws ExcecaoConteudo
	 * @return object
	 */
	public function procurarPorIdentificador( int $id, bool $complemento = true ): object{

		//Verificando
		if( $this->verificarExistenciaPorId( $id ) )
			//Retornando
			return $this->getComplemento( $this->repositorio->procurarPorIdentificador( $id ), $complemento );
		else
			//Lançando a exceção
			throw new ExcecaoConteudo( sprintf( Mensagem::CONTEUDO_NAO_EXISTE, $this->repositorio->configuracao->nome ), RESPOSTA_NAO_ENCONTRADO );

	}

	/**
	 * Procurando o conteúdo por status e pelo seu slug
	 *
	 * @access public
	 * @param int $status Status
	 * @param ?string $slug Slug
	 * @param bool $complemento Obtendo o complemento ou não
	 * @uses $this->verificarExistenciaPorStatusPorSlug Verificando a existência do conteúdo pelo seu status e pelo seu slug
	 * @uses App\Repositorio\Repositorio->procurarPorSlug Procurando o conteúdo pelo seu slug
	 * @uses $this->getComplemento Obtendo o complemento ou não
	 * @throws ExcecaoConteudo
	 * @return object
	 */
	public function procurarPorStatusPorSlug( int $status, ?string $slug, bool $complemento = true ): object{

		//Verificando
		if( $this->verificarExistenciaPorStatusPorSlug( $status, $slug ) )
			//Retornando
			return $this->getComplemento( $this->repositorio->procurarPorSlug( $slug ), $complemento );
		else
			//Lançando a exceção
			throw new ExcecaoConteudo( sprintf( Mensagem::CONTEUDO_NAO_EXISTE, $this->repositorio->configuracao->nome ), RESPOSTA_NAO_ENCONTRADO );

	}

	/**
	 * Procurando o conteúdo pelo seu slug
	 *
	 * @access public
	 * @param ?string $slug Slug
	 * @param bool $complemento Obtendo o complemento ou não
	 * @uses $this->verificarExistenciaPorSlug Verificando a existência do conteúdo pelo seu slug
	 * @uses App\Repositorio\Repositorio->procurarPorSlug Procurando o conteúdo pelo seu slug
	 * @uses $this->getComplemento Obtendo o complemento ou não
	 * @throws ExcecaoConteudo
	 * @return object
	 */
	public function procurarPorSlug( ?string $slug, bool $complemento = true ): object{

		//Verificando
		if( $this->verificarExistenciaPorSlug( $slug ) )
			//Retornando
			return $this->getComplemento( $this->repositorio->procurarPorSlug( $slug ), $complemento );
		else
			//Lançando a exceção
			throw new ExcecaoConteudo( sprintf( Mensagem::CONTEUDO_NAO_EXISTE, $this->repositorio->configuracao->nome ), RESPOSTA_NAO_ENCONTRADO );

	}

	/**
	 * Procurando o conteúdo pelo seu email
	 *
	 * @access public
	 * @param ?string $email Email
	 * @param bool $complemento Obtendo o complemento ou não
	 * @uses $this->verificarExistenciaPorEmail Verificando a existência do conteúdo pelo seu email
	 * @uses App\Repositorio\Repositorio->procurarPorEmail Procurando o conteúdo pelo seu email
	 * @uses $this->getComplemento Obtendo o complemento ou não
	 * @throws ExcecaoConteudo
	 * @return object
	 */
	public function procurarPorEmail( ?string $email, bool $complemento = true ): object{

		//Verificando
		if( $this->verificarExistenciaPorEmail( $email ) )
			//Retornando
			return $this->getComplemento( $this->repositorio->procurarPorEmail( $email ), $complemento );
		else
			//Lançando a exceção
			throw new ExcecaoConteudo( sprintf( Mensagem::CONTEUDO_NAO_EXISTE, $this->repositorio->configuracao->nome ), RESPOSTA_NAO_ENCONTRADO );

	}

	/*
	 * LISTAGENS
	 */

	/**
	 * Listando os conteúdos pelo seu identificador
	 *
	 * @access public
	 * @param int $id Identificador
	 * @param bool $complemento Obtendo o complemento ou não
	 * @uses App\Repositorio\Repositorio->listarPorIdentificador Listando os conteúdos pelo seu identificador
	 * @uses $this->getComplemento Obtendo o complemento ou não
	 * @return mixed
	 */
	public function listarPorIdentificador( int $id, bool $complemento = true ): mixed{

		//Retornando
		return $this->getComplemento( $this->repositorio->listarPorIdentificador( $id ), $complemento );

	}

	/**
	 * Listando os conteúdos por status, por termos, de forma ordenada e limitada
	 *
	 * @access public
	 * @param int $status Status
	 * @param array $termos Termos
	 * @param ?string $ordem Ordem de aparição dos conteúdos
	 * @param int $limitador Limitador de conteúdos por página
	 * @param bool $complemento Obtendo o complemento ou não
	 * @uses App\Repositorio\Repositorio->listarPorStatusPorTermosOrdenadoLimitado Listando os conteúdos por status, por termos, de forma ordenada e limitada
	 * @uses $this->getComplemento Obtendo o complemento ou não
	 * @return mixed
	 */
	public function listarPorStatusPorTermosOrdenadoLimitado( int $status, array $termos, ?string $ordem, int $limitador, bool $complemento = true ): mixed{

		//Retornando
		return $this->getComplemento( $this->repositorio->listarPorStatusPorTermosOrdenadoLimitado( $status, $termos, $ordem, $limitador ), $complemento );

	}

	/**
	 * Listando os conteúdos por status, de forma ordenada e limitada
	 *
	 * @access public
	 * @param int $status Status
	 * @param ?string $ordem Ordem de aparição dos conteúdos
	 * @param int $limitador Limitador de conteúdos por página
	 * @param bool $complemento Obtendo o complemento ou não
	 * @uses App\Repositorio\Repositorio->listarPorStatusOrdenadoLimitado Listando os conteúdos por status, de forma ordenada e limitada
	 * @uses $this->getComplemento Obtendo o complemento ou não
	 * @return mixed
	 */
	public function listarPorStatusOrdenadoLimitado( int $status, ?string $ordem, int $limitador, bool $complemento = true ): mixed{

		//Retornando
		return $this->getComplemento( $this->repositorio->listarPorStatusOrdenadoLimitado( $status, $status, $limitador, $ordem ), $complemento );

	}

	/**
	 * Listando os conteúdos por status e de forma ordenada
	 *
	 * @access public
	 * @param int $status Status
	 * @param ?string $ordem Ordem de aparição dos conteúdos
	 * @param bool $complemento Obtendo o complemento ou não
	 * @uses App\Repositorio\Repositorio->listarPorStatusOrdenado Listando os conteúdos por status e de forma ordenada
	 * @uses $this->getComplemento Obtendo o complemento ou não
	 * @return mixed
	 */
	public function listarPorStatusOrdenado( int $status, ?string $ordem, bool $complemento = true ): mixed{

		//Retornando
		return $this->getComplemento( $this->repositorio->listarPorStatusOrdenado( $status, $ordem ), $complemento );

	}

	/**
	 * Listando por status, de forma ordenada e por quantidade
	 *
	 * @access public
	 * @param int $status Status
	 * @param ?string $ordem Ordem de aparição dos conteúdos
	 * @param int $quantidade Quantidade de conteúdos para serem exibidos
	 * @param bool $complemento Obtendo o complemento ou não
	 * @uses App\Repositorio\Repositorio->listarPorStatusOrdenadoPorQuantidade Listando por status, de forma ordenada e por quantidade
	 * @uses $this->getComplemento Obtendo o complemento ou não
	 * @return mixed
	 */
	public function listarPorStatusOrdenadoPorQuantidade( int $status, ?string $ordem, int $quantidade, bool $complemento = true ): mixed{

		//Retornando
		return $this->getComplemento( $this->repositorio->listarPorStatusOrdenadoPorQuantidade( $status, $ordem, $quantidade ), $complemento );

	}

	/**
	 * Listando de forma ordenada e por quantidade
	 *
	 * @access public
	 * @param ?string $ordem Ordem de aparição dos conteúdos
	 * @param int $quantidade Quantidade de conteúdos para serem exibidos
	 * @param bool $complemento Obtendo o complemento ou não
	 * @uses App\Repositorio\Repositorio->listarOrdenadoPorQuantidade Listando de forma ordenada e por quantidade
	 * @uses $this->getComplemento Obtendo o complemento ou não
	 * @return mixed
	 */
	public function listarOrdenadoPorQuantidade( ?string $ordem, int $quantidade, bool $complemento = true ): mixed{

		//Retornando
		return $this->getComplemento( $this->repositorio->listarOrdenadoPorQuantidade( $ordem, $quantidade ), $complemento );

	}

	/**
	 * Listando de forma ordenada
	 *
	 * @access public
	 * @param ?string $ordem Ordem de aparição dos conteúdos
	 * @param bool $complemento Obtendo o complemento ou não
	 * @uses App\Repositorio\Repositorio->listarOrdenado Listando de forma ordenada
	 * @uses $this->getComplemento Obtendo o complemento ou não
	 * @return mixed
	 */
	public function listarOrdenado( ?string $ordem, bool $complemento = true ): mixed{

		//Retornando
		return $this->getComplemento( $this->repositorio->listarOrdenado( $ordem ), $complemento );

	}

	/*
	 * CONTAGENS
	 */

	/**
	 * Contando a quantidade de conteúdos por status
	 *
	 * @access public
	 * @param int $status Status
	 * @uses App\Repositorio\Repositorio->contarPorStatus Contando a quantidade de conteúdos por status
	 * @return int
	 */
	public function contarPorStatus( int $status ): int{

		//Retornando
		return $this->repositorio->contarPorStatus( $status );

	}

	/**
	 * Contando a quantidade de conteúdos por status e por termos
	 *
	 * @access public
	 * @param int $status Status
	 * @param array $termos Termos
	 * @uses App\Repositorio\Repositorio->contarPorStatusPorTermos Contando a quantidade de conteúdos por status e por termos
	 * @return int
	 */
	public function contarPorStatusPorTermos( int $status, array $termos ): int{

		//Retornando
		return $this->repositorio->contarPorStatusPorTermos( $status, $termos );

	}

}