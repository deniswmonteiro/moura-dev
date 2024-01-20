<?php
//Definindo o namespace
namespace App\Repositorio\BDR;

//Definindo as classes usadas
use App\Excecao\BancoDeDados as ExcecaoBancoDeDados;
use App\Excecao\Conteudo as ExcecaoConteudo;
use App\Conexao\BDR\Conexao;
use App\Mensagem\Mensagem;
use App\Modelo\Modelo;
use App\Util\Util;
use PDOException;
use stdClass;
use PDO;

/**
 * Repositório para Banco de Dados
 *
 * @abstract
 * @author Lucas Dantas <lucas@moveon.dev>
 * @package Repositorio
 * @subpackage BDR
 * @version 1.0
 * @since 1.0 28/10/2023 20:47
 */
Abstract Class Repositorio{

	/**
	 * Instância
	 *
	 * @access private
	 * @static
	 * @var array
	 */
	protected static array $instancia	= [];

	/**
	 * Método construtor da classe
	 *
	 * @access public
	 * @param string $modelo Modelo
	 * @param bool $formatar Formatar os campos ou não
	 * @param bool $remover Remover ou não os campos
	 * @param bool $excecao Exibindo ou não a exceção
	 * @param ?object $conexao Conexão
	 * @param ?object $configuracao Configuração
	 * @uses Conexao::getInstancia Obtendo a instância
	 * @uses $this->configuracao Configurando o repositório
	 */
	public function __construct( protected string $modelo, public bool $formatar = true, public bool $remover = true, public bool $excecao = true, protected ?object $conexao = null, protected ?object $configuracao = null ){

		$this->conexao	= Conexao::getInstancia()->conexao;
		$this->modelo	= $modelo;
		$this->configuracao();

	}

	/**
	 * Configurando o repositório
	 *
	 * @access public
	 * @return void
	 */
	public function configuracao(): void{

		//Definindo o modelo
		$modelo					= new $this->modelo();

		//Definindo algumas configurações
		$this->configuracao			= new stdclass();
		$this->configuracao->tabela	= $modelo->configuracao[ 'banco' ][ 'tabela' ];
		$this->configuracao->nome	= $modelo->configuracao[ 'nome' ];

		//Listando
		foreach( $modelo->atributos as $chave => $campo )
			//Definindo
			$array[ $chave ]	= $campo;

		//Definindo os campos
		$this->configuracao->campos	= implode( ',', array_keys( $array ) );

	}

	/**
	 * Obtendo a instância
	 *
	 * @access public
	 * @static
	 * @param string $classe Nome da classe
	 * @param string $modelo Modelo
	 * @return Repositorio
	 */
	public static function getInstancia( string $classe, string $modelo ): Repositorio{

		//Verificando
		if( !array_key_exists( $modelo, self::$instancia ) )
			//Definindo
			self::$instancia[ $modelo ]	= new $classe( $modelo );

		//Retornando
		return self::$instancia[ $modelo ];

	}

	/*
	 * VERIFICAÇÕES
	 */

	/**
	 * Verificando a existência do conteúdo
	 *
	 * @access public
	 * @param ?string $complemento Complemento que virá de outros métodos
	 * @param array $parametros Parâmetros que virão de outros métodos
	 * @throws ExcecaoBancoDeDados
	 * @return bool
	 */
	public function verificarExistencia( ?string $complemento = null, array $parametros = [] ): bool{

		try{

			//Preparando a consulta
			$pdo	= $this->conexao->prepare( 'SELECT COUNT(*) AS quantidade FROM ' . $this->configuracao->tabela . ' WHERE id IS NOT NULL ' . $complemento );

			//Verificando
			if( count( $parametros ) > 0 )
				//Listando
				foreach( $parametros as $campo => $configuracao )
					//Definindo os valores
					$pdo->bindValue( $campo, $configuracao[ 'valor' ], $configuracao[ 'tipo' ] );

			//Executando
			$pdo->execute();

			//Definindo os valores
			$objeto	= $pdo->fetch( PDO::FETCH_OBJ );

			//Retornando
			return ( $objeto->quantidade > 0 ) ?? false;

		}catch( PDOException $e ){

			//Lançando a exceção
			throw new ExcecaoBancoDeDados( Mensagem::VERIFICAR, $e, resposta: RESPOSTA_SOLICITACAO_INCORRETA );

		}

	}

	/**
	 * Verificando a existência do conteúdo pelo seu identificador
	 *
	 * @access public
	 * @param int $id Identificador
	 * @param ?string $complemento Complemento que virá de outros métodos
	 * @param array $parametros Parâmetros que virão de outros métodos
	 * @uses $this->verificarExistencia Verificando a existência do conteúdo
	 * @return bool
	 */
	public function verificarExistenciaPorId( int $id, ?string $complemento = null, array $parametros = [] ): bool{

		//Definindo o complemento
		$complemento	= 'AND id = :id ' . $complemento;

		//Definindo os parâmetros
		$parametros		= [ ':id' => [ 'valor' => $id, 'tipo' => PDO::PARAM_INT ], ...$parametros ];

		//Verificando e retornando
		return $this->verificarExistencia( $complemento, $parametros );

	}

	/**
	 * Verificando a existência do conteúdo por status e pelo seu slug
	 *
	 * @access public
	 * @param int $status Status
	 * @param ?string $string Slug
	 * @param ?string $complemento Complemento que virá de outros métodos
	 * @param array $parametros Parâmetros que virão de outros métodos
	 * @uses $this->verificarExistenciaPorStatus Verificando a existência do conteúdo por status
	 * @return bool
	 */
	public function verificarExistenciaPorStatusPorSlug( int $status, ?string $slug, ?string $complemento = null, array $parametros = [] ): bool{

		//Definindo o complemento
		$complemento	= 'AND slug = :slug ' . $complemento;

		//Definindo os parâmetros
		$parametros		= [ ':slug' => [ 'valor' => $slug, 'tipo' => PDO::PARAM_STR ], ...$parametros ];

		//Verificando e retornando
		return $this->verificarExistenciaPorStatus( $status, $complemento, $parametros );

	}

	/**
	 * Verificando a existência do conteúdo por status
	 *
	 * @access public
	 * @param int $status Status
	 * @param ?string $complemento Complemento que virá de outros métodos
	 * @param array $parametros Parâmetros que virão de outros métodos
	 * @uses $this->verificarExistencia Verificando a existência do conteúdo
	 * @return bool
	 */
	public function verificarExistenciaPorStatus( int $status, ?string $complemento = null, array $parametros = [] ): bool{

		//Definindo o complemento
		$complemento	= 'AND status = :status ' . $complemento;

		//Definindo os parâmetros
		$parametros		= [ ':status' => [ 'valor' => $status, 'tipo' => PDO::PARAM_INT ], ...$parametros ];

		//Verificando e retornando
		return $this->verificarExistencia( $complemento, $parametros );

	}

	/**
	 * Verificando a existência do conteúdo pelo seu slug
	 *
	 * @access public
	 * @param ?string $string Slug
	 * @param ?string $complemento Complemento que virá de outros métodos
	 * @param array $parametros Parâmetros que virão de outros métodos
	 * @uses $this->verificarExistencia Verificando a existência do conteúdo
	 * @return bool
	 */
	public function verificarExistenciaPorSlug( ?string $slug, ?string $complemento = null, array $parametros = [] ): bool{

		//Definindo o complemento
		$complemento	= 'AND slug = :slug ' . $complemento;

		//Definindo os parâmetros
		$parametros		= [ ':slug' => [ 'valor' => $slug, 'tipo' => PDO::PARAM_STR ], ...$parametros ];

		//Verificando e retornando
		return $this->verificarExistencia( $complemento, $parametros );

	}

	/**
	 * Verificando a existência do conteúdo pelo seu email
	 *
	 * @access public
	 * @param ?string $string Email
	 * @param ?string $complemento Complemento que virá de outros métodos
	 * @param array $parametros Parâmetros que virão de outros métodos
	 * @uses $this->verificarExistencia Verificando a existência do conteúdo
	 * @return bool
	 */
	public function verificarExistenciaPorEmail( ?string $email, ?string $complemento = null, array $parametros = [] ): bool{

		//Definindo o complemento
		$complemento	= 'AND email = :email ' . $complemento;

		//Definindo os parâmetros
		$parametros		= [ ':email' => [ 'valor' => $email, 'tipo' => PDO::PARAM_STR ], ...$parametros ];

		//Verificando e retornando
		return $this->verificarExistencia( $complemento, $parametros );

	}

	/*
	 * INSERÇÕES
	 */

	/**
	 * Inserindo o conteúdo
	 *
	 * @access public
	 * @param Modelo $modelo Modelo
	 * @throws ExcecaoBancoDeDados
	 * @return int
	 */
	public function inserir( Modelo $modelo ): int{

		try{

			//Definindo os campos
			$campos	= $modelo->configuracao[ 'banco' ][ 'campos' ];

			//Preparando a consulta
			$pdo	= $this->conexao->prepare( 'INSERT INTO ' . $this->configuracao->tabela . '(' . implode( ',', $campos ) . ') VALUES(' . substr( str_repeat( '?,', $modelo->configuracao[ 'banco' ][ 'quantidade' ] ), 0, -1 ) . ')' );

			//Listando
			foreach( $campos as $i => $campo )
				//Definindo os valores
				$pdo->bindValue( ( $i + 1 ), $modelo->$campo, $modelo->atributos[ $campo ][ 'tipo-campo' ] );

			//Executando
			$pdo->execute();

			//Retornando
			return $this->conexao->lastInsertId();

		}catch( PDOException $e ){

			//Lançando a exceção
			throw new ExcecaoBancoDeDados( Mensagem::INSERIR, $e, resposta: RESPOSTA_SOLICITACAO_INCORRETA );

		}

	}

	/*
	 * PROCURAS
	 */

	/**
	 * Procurando o conteúdo
	 *
	 * @access public
	 * @param ?string $complemento Complemento que virá de outros métodos
	 * @param array $parametros Parâmetros que virão de outros métodos
	 * @throws ExcecaoBancoDeDados
	 * @return object
	 */
	public function procurar( ?string $complemento = null, array $parametros = [] ): object{

		try{

			//Preparando a consulta
			$pdo	= $this->conexao->prepare( 'SELECT ' . $this->configuracao->campos . ' FROM ' . $this->configuracao->tabela . ' WHERE id IS NOT NULL ' . $complemento );

			//Verificando
			if( count( $parametros ) > 0 )
				//Listando
				foreach( $parametros as $campo => $configuracao )
					//Definindo os valores
					$pdo->bindValue( $campo, $configuracao[ 'valor' ], $configuracao[ 'tipo' ] );

			//Executando
			$pdo->execute();

			//Retornando
			return (object) ( new $this->modelo( $pdo->fetch( PDO::FETCH_ASSOC ), $this->formatar, $this->remover ) )->propriedades;

		}catch( PDOException $e ){

			//Lançando a exceção
			throw new ExcecaoBancoDeDados( Mensagem::PROCURAR, $e, resposta: RESPOSTA_SOLICITACAO_INCORRETA );

		}

	}

	/**
	 * Procurando o conteúdo pelo seu identificador
	 *
	 * @access public
	 * @param int $id Identificador
	 * @param ?string $complemento Complemento que virá de outros métodos
	 * @param array $parametros Parâmetros que virão de outros métodos
	 * @uses $this->procurar Procurando o conteúdo
	 * @return object
	 */
	public function procurarPorIdentificador( int $id, ?string $complemento = null, array $parametros = [] ): object{

		//Definindo o complemento
		$complemento	= 'AND id = :id ' . $complemento;

		//Definindo os parâmetros
		$parametros		= [ ':id' => [ 'valor' => $id, 'tipo' => PDO::PARAM_INT ], ...$parametros ];

		//Procurando e retornando
		return $this->procurar( $complemento, $parametros );

	}

	/**
	 * Procurando o conteúdo pelo seu slug
	 *
	 * @access public
	 * @param ?string $slug Slug
	 * @param ?string $complemento Complemento que virá de outros métodos
	 * @param array $parametros Parâmetros que virão de outros métodos
	 * @uses $this->procurar Procurando o conteúdo
	 * @return object
	 */
	public function procurarPorSlug( ?string $slug, ?string $complemento = null, array $parametros = [] ): object{

		//Definindo o complemento
		$complemento	= 'AND slug = :slug ' . $complemento;

		//Definindo os parâmetros
		$parametros		= [ ':slug' => [ 'valor' => $slug, 'tipo' => PDO::PARAM_STR ], ...$parametros ];

		//Procurando e retornando
		return $this->procurar( $complemento, $parametros );

	}

	/*
	 * LISTAGENS
	 */

	/**
	 * Listando os conteúdos
	 *
	 * @access public
	 * @param ?string $complemento Complemento que virá de outros métodos
	 * @param array $parametros Parâmetros que virão de outros métodos
	 * @throws ExcecaoConteudo
	 * @throws ExcecaoBancoDeDados
	 * @return mixed
	 */
	public function listar( ?string $complemento = null, array $parametros = [] ): mixed{

		try{

			//Preparando a consulta
			$pdo	= $this->conexao->prepare( 'SELECT ' . $this->configuracao->campos . ' FROM ' . $this->configuracao->tabela . ' WHERE id IS NOT NULL ' . $complemento );

			//Verificando
			if( count( $parametros ) > 0 )
				//Listando
				foreach( $parametros as $campo => $configuracao )
					//Definindo os valores
					$pdo->bindValue( $campo, $configuracao[ 'valor' ], $configuracao[ 'tipo' ] );

			//Executando
			$pdo->execute();

			//Definindo os registros
			$conteudos	= $pdo->fetchAll( PDO::FETCH_ASSOC );

			//Verificando
			if( count( $conteudos ) > 0 )
				//Listando os conteúdos
				foreach( $conteudos as $conteudo )
					//Definindo
					$array[]	= (object) ( new $this->modelo( $conteudo, $this->formatar, $this->remover ) )->propriedades;
			//Exibindo ou não a exceção
			else if( $this->excecao )
				//Lançando a exceção
				throw new ExcecaoConteudo( sprintf( Mensagem::NENHUM_REGISTRO_ENCONTRADO, $this->configuracao->nome ), RESPOSTA_NAO_ENCONTRADO );
			else
				//Retornando
				return null;

			//Retornando
			return $array;

		}catch( PDOException $e ){

			//Lançando a exceção
			throw new ExcecaoBancoDeDados( Mensagem::LISTAR, $e, resposta: RESPOSTA_SOLICITACAO_INCORRETA );

		}

	}

	/**
	 * Listando os conteúdos por status, de forma ordenada e por quantidade
	 *
	 * @access public
	 * @param int $status Status
	 * @param ?string $ordem Ordem de aparição dos conteúdos
	 * @param int $quantidade Quantidade de conteúdos
	 * @param ?string $complemento Complemento que virá de outros métodos
	 * @param array $parametros Parâmetros que virão de outros métodos
	 * @uses $this->listarPorStatusOrdenado Listando os conteúdos por status e de forma ordenada
	 * @return mixed
	 */
	public function listarPorStatusOrdenadoPorQuantidade( int $status, ?string $ordem, int $quantidade, ?string $complemento = null, array $parametros = [] ): mixed{

		//Definindo o complemento
		$complemento	= 'LIMIT :limitador ' . $complemento;

		//Definindo os parâmetros
		$parametros		= [ ':limitador' => [ 'valor' => $quantidade, 'tipo' => PDO::PARAM_INT ], ...$parametros ];

		//Listando os conteúdos e retornando
		return $this->listarPorStatusOrdenado( $status, $ordem, $complemento, $parametros );

	}

	/**
	 * Listando os conteúdos por status, de forma ordenada e limitada
	 *
	 * @access public
	 * @param int $status Status
	 * @param ?string $ordem Ordem de aparição dos conteúdos
	 * @param int $limitador Limitador de conteúdos por página
	 * @param ?string $complemento Complemento que virá de outros métodos
	 * @param array $parametros Parâmetros que virão de outros métodos
	 * @uses $this->listarPorStatusOrdenado Listando os conteúdos por status e de forma ordenada
	 * @return mixed
	 */
	public function listarPorStatusOrdenadoLimitado( int $status, ?string $ordem, int $limitador, ?string $complemento = null, array $parametros = [] ): mixed{

		//Definindo o complemento
		$complemento	= 'LIMIT :limitador, ' . REGISTROS_POR_PAGINA . ' ' . $complemento;

		//Definindo os parâmetros
		$parametros		= [ ':limitador' => [ 'valor' => $limitador, 'tipo' => PDO::PARAM_INT ], ...$parametros ];

		//Listando os conteúdos e retornando
		return $this->listarPorStatusOrdenado( $status, $ordem, $complemento, $parametros );

	}

	/**
	 * Listando os conteúdos por status e de forma ordenada
	 *
	 * @access public
	 * @param int $status Status
	 * @param ?string $ordem Ordem de aparição dos conteúdos
	 * @param ?string $complemento Complemento que virá de outros métodos
	 * @param array $parametros Parâmetros que virão de outros métodos
	 * @uses App\Util\Util::definirOrdemBancoDeDados Definindo a ordem dos conteúdos para o banco de dados
	 * @uses $this->listarPorStatus Listando os conteúdos por status
	 * @return mixed
	 */
	public function listarPorStatusOrdenado( int $status, ?string $ordem, ?string $complemento = null, array $parametros = [] ): mixed{

		//Definindo o complemento
		$complemento	= 'ORDER BY ' . Util::definirOrdemBancoDeDados( $ordem ) . ' ' . $complemento;

		//Listando os conteúdos e retornando
		return $this->listarPorStatus( $status, $complemento, $parametros );

	}

	/**
	 * Listando por status
	 *
	 * @access public
	 * @param int $status Status
	 * @param ?string $complemento Complemento que virá de outros métodos
	 * @param array $parametros Parâmetros que virão de outros métodos
	 * @uses $this->listar Listando
	 * @return mixed
	 */
	public function listarPorStatus( int $status, ?string $complemento = null, array $parametros = [] ): mixed{

		//Definindo o complemento
		$complemento	= 'AND status = :status ' . $complemento;

		//Definindo os parâmetros
		$parametros		= [ ':status' => [ 'valor' => $status, 'tipo' => PDO::PARAM_INT ], ...$parametros ];

		//Listando os conteúdos e retornando
		return $this->listar( $complemento, $parametros );

	}

	/**
	 * Listando os conteúdos de forma ordenada e por quantidade
	 *
	 * @access public
	 * @param ?string $ordem Ordem de aparição dos conteúdos
	 * @param int $quantidade Quantidade de conteúdos
	 * @param ?string $complemento Complemento que virá de outros métodos
	 * @param array $parametros Parâmetros que virão de outros métodos
	 * @uses $this->listarOrdenado Listando os conteúdos de forma ordenada
	 * @return mixed
	 */
	public function listarOrdenadoPorQuantidade( ?string $ordem, int $quantidade, ?string $complemento = null, array $parametros = [] ): mixed{

		//Definindo o complemento
		$complemento	= 'LIMIT :quantidade ' . $complemento;

		//Definindo os parâmetros
		$parametros		= [ ':quantidade' => [ 'valor' => $quantidade, 'tipo' => PDO::PARAM_INT ], ...$parametros ];

		//Retornando
		return $this->listarOrdenado( $ordem, $complemento, $parametros );

	}

	/**
	 * Listando os conteúdos de forma ordenada
	 *
	 * @access public
	 * @param ?string $ordem Ordem de aparição dos conteúdos
	 * @param ?string $complemento Complemento que virá de outros métodos
	 * @param array $parametros Parâmetros que virão de outros métodos
	 * @uses App\Util\Util::definirOrdemBancoDeDados Definindo a ordem dos conteúdos para o banco de dados
	 * @uses $this->listar Listando os conteúdos
	 * @return mixed
	 */
	public function listarOrdenado( ?string $ordem, ?string $complemento = null, array $parametros = [] ): mixed{

		//Definindo o complemento
		$complemento	= 'ORDER BY ' . Util::definirOrdemBancoDeDados( $ordem ) . ' ' . $complemento;

		//Retornando
		return $this->listar( $complemento, $parametros );

	}

	/**
	 * Listando os conteúdos por status, por termos, de forma ordenada e limitada
	 *
	 * @access public
	 * @param int $status Status
	 * @param array $termos Termos
	 * @param ?string $ordem Ordem de aparição dos conteúdos
	 * @param int $limitador Limitador de conteúdos por página
	 * @param ?string $complemento Complemento que virá de outros métodos
	 * @param array $parametros Parâmetros que virão de outros métodos
	 * @uses $this->listarPorStatusPorTermosOrdenado Listando os conteúdos por status, por termos e de forma ordenada
	 * @return mixed
	 */
	public function listarPorStatusPorTermosOrdenadoLimitado( int $status, array $termos, ?string $ordem, int $limitador, ?string $complemento = null, array $parametros = [] ): mixed{

		//Definindo o complemento
		$complemento	= 'LIMIT :limitador, ' . REGISTROS_POR_PAGINA . ' ' . $complemento;

		//Definindo os parâmetros
		$parametros		= [ ':limitador' => [ 'valor' => $limitador, 'tipo' => PDO::PARAM_INT ], ...$parametros ];

		//Listando os conteúdos e retornando
		return $this->listarPorStatusPorTermosOrdenado( $status, $termos, $ordem, $complemento, $parametros );

	}

	/**
	 * Listando os conteúdos por status, por termos e de forma ordenada
	 *
	 * @access public
	 * @param int $status Status
	 * @param array $termos Termos
	 * @param ?string $ordem Ordem de aparição dos conteúdos
	 * @param ?string $complemento Complemento que virá de outros métodos
	 * @param array $parametros Parâmetros que virão de outros métodos
	 * @uses App\Util\Util::definirOrdemBancoDeDados Definindo a ordem dos conteúdos para o banco de dados
	 * @uses $this->listarPorStatusPorTermos Listando os conteúdos por status e por termos
	 * @return mixed
	 */
	public function listarPorStatusPorTermosOrdenado( int $status, array $termos, ?string $ordem, ?string $complemento = null, array $parametros = [] ): mixed{

		//Definindo o complemento
		$complemento	= 'ORDER BY ' . Util::definirOrdemBancoDeDados( $ordem ) . ' ' . $complemento;

		//Listando os conteúdos e retornando
		return $this->listarPorStatusPorTermos( $status, $termos, $complemento, $parametros );

	}

	/**
	 * Listando os conteúdos por status e por termos
	 *
	 * @access public
	 * @param int $status Status
	 * @param array $termos Termos
	 * @param ?string $complemento Complemento que virá de outros métodos
	 * @param array $parametros Parâmetros que virão de outros métodos
	 * @uses $this->listarPorStatus Listando os conteúdos por status
	 * @return mixed
	 */
	public function listarPorStatusPorTermos( int $status, array $termos, ?string $complemento = null, array $parametros = [] ): mixed{

		//Verificando a existência do nome nos termos de pesquisa
		if( isset( $termos[ 'nome' ] ) && $termos[ 'nome' ] != '' ){

			//Definindo o complemento
			$complemento	= 'AND name LIKE :nome ' . $complemento;

			//Definindo os parâmetros
			$parametros		= [ ':nome' => [ 'valor' => '%' . $termos[ 'nome' ] . '%', 'tipo' => PDO::PARAM_STR ], ...$parametros ];

		}

		//Verificando a existência da categoria nos termos de pesquisa
		if( isset( $termos[ 'categoria' ] ) && $termos[ 'categoria' ] != '' ){

			//Definindo o complemento
			$complemento	= 'AND category = :categoria ' . $complemento;

			//Definindo os parâmetros
			$parametros		= [ ':categoria' => [ 'valor' => $termos[ 'categoria' ], 'tipo' => PDO::PARAM_INT ], ...$parametros ];

		}

		//Contando os conteúdos e retornando
		return $this->listarPorStatus( $status, $complemento, $parametros );

	}

	/*
	 * CONTAGENS
	 */

	/**
	 * Contando a quantidade de conteúdos
	 *
	 * @access public
	 * @param ?string $complemento Complemento que virá de outros métodos
	 * @param array $parametros Parâmetros que virão de outros métodos
	 * @throws ExcecaoBancoDeDados
	 * @return int
	 */
	public function contar( ?string $complemento = null, array $parametros = [] ): int{

		try{

			//Preparando a consulta
			$pdo	= $this->conexao->prepare( 'SELECT COUNT(*) AS quantidade FROM ' . $this->configuracao->tabela . ' WHERE id IS NOT NULL ' . $complemento );

			//Verificando
			if( count( $parametros ) > 0 )
				//Listando
				foreach( $parametros as $campo => $configuracao )
					//Definindo os valores
					$pdo->bindValue( $campo, $configuracao[ 'valor' ], $configuracao[ 'tipo' ] );

			//Executando
			$pdo->execute();

			//Definindo os valores
			$objeto	= $pdo->fetch( PDO::FETCH_OBJ );

			//Retornando
			return $objeto->quantidade;

		}catch( PDOException $e ){

			//Lançando a exceção
			throw new ExcecaoBancoDeDados( Mensagem::CONTAR, $e, resposta: RESPOSTA_SOLICITACAO_INCORRETA );

		}

	}

	/**
	 * Contando a quantidade de conteúdos por status e por termos
	 *
	 * @access public
	 * @param int $status Status
	 * @param array $termos Termos
	 * @param ?string $complemento Complemento que virá de outros métodos
	 * @param array $parametros Parâmetros que virão de outros métodos
	 * @uses $this->contar Contando
	 * @return mixed
	 */
	public function contarPorStatusPorTermos( int $status, array $termos, ?string $complemento = null, array $parametros = [] ): mixed{

		//Verificando a existência do nome nos termos de pesquisa
		if( isset( $termos[ 'nome' ] ) && $termos[ 'nome' ] != '' ){

			//Definindo o complemento
			$complemento	= 'AND name LIKE :nome ' . $complemento;

			//Definindo os parâmetros
			$parametros		= [ ':nome' => [ 'valor' => '%' . $termos[ 'nome' ] . '%', 'tipo' => PDO::PARAM_STR ], ...$parametros ];

		}

		//Verificando a existência da categoria nos termos de pesquisa
		if( isset( $termos[ 'categoria' ] ) && $termos[ 'categoria' ] != '' ){

			//Definindo o complemento
			$complemento	= 'AND category = :categoria ' . $complemento;

			//Definindo os parâmetros
			$parametros		= [ ':categoria' => [ 'valor' => $termos[ 'categoria' ], 'tipo' => PDO::PARAM_INT ], ...$parametros ];

		}

		//Contando os conteúdos e retornando
		return $this->contarPorStatus( $status, $complemento, $parametros );

	}

	/**
	 * Contando a quantidade de conteúdos por status
	 *
	 * @access public
	 * @param int $status Status
	 * @param ?string $complemento Complemento que virá de outros métodos
	 * @param array $parametros Parâmetros que virão de outros métodos
	 * @uses $this->contar Contando
	 * @return mixed
	 */
	public function contarPorStatus( int $status, ?string $complemento = null, array $parametros = [] ): mixed{

		//Definindo o complemento
		$complemento	= 'AND status = :status ' . $complemento;

		//Definindo os parâmetros
		$parametros		= [ ':status' => [ 'valor' => $status, 'tipo' => PDO::PARAM_INT ], ...$parametros ];

		//Contando os conteúdos e retornando
		return $this->contar( $complemento, $parametros );

	}

}