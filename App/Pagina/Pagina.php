<?php
//Definindo o namespace
namespace App\Pagina;

/**
 * Classe da exibição das páginas
 *
 * @author Lucas Dantas <lucas@moveon.dev>
 * @package App
 * @subpackage Pagina
 * @version 1.0
 * @since 1.0 28/10/2023 19:28
 */
Class Pagina{

	/**
	 * Propriedades
	 *
	 * @access public
	 * @var array
	 */
	public array $propriedades	= [];

	/**
	 * Método construtor da classe
	 *
	 * @access public
	 * @param array $queryString Query string
	 * @uses $this->formatarQueryString Formatando a query string
	 * @uses $this->despachar Despachando a página
	 */
	public function __construct( array $queryString ){

		$this->formatarQueryString( $queryString );
		$this->despachar();

	}

	/**
	 * Atribuições
	 *
	 * @access public
	 * @param string $chave Chave do campo
	 * @param mixed $valor Valor do campo
	 * @return void
	 */
	public function __set( string $chave, mixed $valor ): void{

		//Definindo
        $this->propriedades[ $chave ]	= $valor;

    }

	/**
	 * Getter
	 *
	 * @access public
	 * @param string $chave Chave do campo
	 * @return mixed
	 */
	public function __get( string $chave ): mixed{

		//Returning
        return $this->propriedades[ $chave ] ?? null;

    }

	/**
	 * Formatando a query string
	 *
	 * @access private
	 * @param array $queryString Query string
	 * @return void
	 */
	private function formatarQueryString( array $queryString ): void{

		//Verificando a existência da chave
		if( array_key_exists( 'pagina', $queryString ) )
			//Definindo
			$this->pagina	= $queryString[ 'pagina' ];

		//Verificando a existência da chave
		if( array_key_exists( 'diretorio', $queryString ) )
			//Definindo
			$this->diretorio   = $queryString[ 'diretorio' ];

		//Verificando a existência da chave
		if( array_key_exists( 'tipo', $queryString ) )
			// Defining
			$this->tipo	= $queryString[ 'tipo' ];

		//Verificando a existência da chave
		if( array_key_exists( 'slug', $queryString ) )
			//Definindo
			$this->slug	= $queryString[ 'slug' ];

		//Verificando a existência da chave
		if( array_key_exists( 'curso', $queryString ) )
			//Definindo
			$this->curso	= $queryString[ 'curso' ];

		//Verificando a existência da chave
		if( array_key_exists( 'trilha', $queryString ) )
			//Definindo
			$this->trilha	= $queryString[ 'trilha' ];

		//Verificando a existência da chave
		if( array_key_exists( 'modelo', $queryString ) ){

			//Definindo os itens
			$namespaces		= explode( '-', $queryString[ 'modelo' ] );

			//Definindo
			$this->modelo	= ( count( $namespaces ) > 1 ) ? str_replace( '-', '\\', $namespaces[ 1 ] . '-' . $queryString[ 'modelo' ] ) : $queryString[ 'modelo' ];

		}

		//Definindo a ação
		$this->acao	= ( array_key_exists( 'acao', $queryString ) )? $queryString[ 'acao' ] : 'index';

	}

	/**
	 * Despachando
	 *
	 * @access private
	 * @return void
	 */
	private function despachar(): void{

		//Verificando o tipo
		switch( $this->tipo ){

			//Tratamento
			case 'tratamento':

				//Verificando
				if( !is_null( $this->acao ) )
                    //Definindo
                    $caminho	= DIR_SUBMIT . '/' . $this->acao . '.php';

			break;

			//Padrão
			default:

				//Definindo
				$caminho	= ( !is_null( $this->diretorio ) && !is_null( $this->acao ) ) ? DIR_PHP . '/' . $this->diretorio . '/' . $this->acao . '.php' : DIR_PHP . '/' . $this->diretorio . '/index.php';

			break;

		}

		//Verificando
		if( isset( $caminho ) ){

			//Formatting the path
			$caminho	= realpath( $caminho );

			//Verificando
			if( file_exists( $caminho ) ){

				//Definindo algumas variáveis
				$pagina	= (object) $this->propriedades;

				//Incluindo o arquivo para exibição
				require_once( $caminho );

			}else
				//Redirecionando
				header( 'Location: ' . URL . '/404', TRUE, 301 );

		}

	}

}