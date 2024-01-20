<?php
//Definindo o namespace
namespace App\Modelo;

//Definindo as classes usadas
use App\Excecao\Conteudo as ExcecaoConteudo;
use App\Validacao\Validacao;
use App\Mensagem\Mensagem;
use App\Fabrica\Fabrica;
use JsonSerializable;
use App\Util\Util;

/**
* Classe dos modelos
* Classes básicas são herdadas por ela
*
* @abstract
* @author Lucas Dantas <lucas@moveon.dev>
* @package App
* @subpackage Modelo
* @version 1.0
* @since 1.0 28/10/2023 01:37
*/
Abstract Class Modelo Implements JsonSerializable{

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
	 * @param array $array Array com os campos
	 * @param boolean $formatar Formatar os campos ou não
	 * @param boolean $remover Remover ou não os campos
	 * @uses $this->configuracao Definindo as configurações
	 * @uses $this->validar Validando
	 * @uses $this->formatarData Formatando a data
	 * @uses $this->relacionamento Definindo os relacionamentos
	 * @uses $this->formatarCaracteres Formatando os caracteres dos campos
	 */
	public function __construct( array $array = [], bool $formatar = false, bool $remover = false ){

		//Verificando
		if( !empty( $array ) ){

			//Listando
			foreach( $array as $campo => $valor )
				//Verificando
				if( array_key_exists( $campo, $this->atributos ) ){

					//Definindo
					$this->propriedades[ $campo ]	= ( $valor == '' && ( $this->atributos[ $campo ][ 'tipo' ] == TIPO_INTEIRO || $this->atributos[ $campo ][ 'tipo' ] == TIPO_STRING || $this->atributos[ $campo ][ 'tipo' ] == TIPO_DATA_CUSTOMIZADA ) ) ? null : $valor;
					$campos[]						= $campo;

				}

			//Verificando a levantando a exceção caso os campos não sejam passados
			$campos ?? throw new ExcecaoConteudo( Mensagem::MODELO_INVALIDO, RESPOSTA_SOLICITACAO_INCORRETA );

			//Definindo as configurações
			$this->configuracao( $campos );

			//Validando
			$this->validar( $campos );

			//Formatando a data
			$this->formatarData();

			//Definindo os relacionamentos
			$this->relacionamento();

			//Verificando se é para formatar ou não
			if( $formatar )
				//Formatando
				$this->formatarCaracteres();

			//Verificando se é para remover os campos desnecessários
			if( $remover ){

				//Removendo
				unset( $this->atributos );
				unset( $this->configuracao );

			}

		}

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
	 * Obtendo os atributos
	 *
	 * @access public
	 * @param string $chave Chave do campo
	 * @return mixed
	 */
	public function __get( string $chave ): mixed{

		//Retornando
        return $this->propriedades[ $chave ];

    }

	/**
	 * Definindo as configurações
	 *
	 * @access public
	 * @param array $campos Campos
	 * @return void
	 */
	public function configuracao( array $campos ): void{

		//Verificando
		if( $this->configuracao[ 'tipo' ] == 'BDR' ){

			//Definindo os campos e a quantidade
			$this->configuracao[ 'banco' ][ 'campos' ] 		= $campos;
			$this->configuracao[ 'banco' ][ 'quantidade' ]	= count( $campos );

		}

	}

	/**
	 * Definindo os relacionamentos
	 *
	 * @access private
	 * @uses Fabrica::getTipo Obtendo o tipo
	 * @return void
	 */
	private function relacionamento(): void{

		//Verificando o tipo
		if( $this->configuracao[ 'tipo' ] == 'BDR' )
			//Verificando se existe relacionamento
			if( isset( $this->configuracao[ 'relacionamento' ] ) )
				//Listando os relacionamentos
				foreach( $this->configuracao[ 'relacionamento' ] as $relacionamento )
					//Definindo o controlador
					$this->{ 'controlador' . $relacionamento[ 'campo' ] } = Fabrica::getTipo( $relacionamento[ 'classe' ], $relacionamento[ 'tipo' ] );

	}

	/**
	 * Formatando os caracteres
	 *
	 * @access private
	 * @uses Util::formatarCaracteres Formatando os caracteres
	 * @return void
	 */
	private function formatarCaracteres(): void{

		//Listando os atributos
		foreach( $this->atributos as $campo => $array )
			//Verificando se é para formatar
			if( $array[ 'formatar' ] ){

				//Definindo
				$this->$campo	= !is_null( $this->$campo ) ? Util::formatarCaracteres( $this->$campo ) : null;

				//Verificando
				if( ( $array[ 'tipo' ] == TIPO_DATA_CUSTOMIZADA || $array[ 'tipo' ] == TIPO_DATA ) && !is_null( $this->$campo ) && $this->$campo != '' )
					//Definindo o campo
					$this->$campo	= Util::formatarData( $this->$campo, strlen( $this->$campo ) > 10 ? 'padrão' : 'pt-sem-hora' );

			}

	}

	/**
	 * Formatando a data
	 *
	 * @access private
	 * @uses Util::formatarData Formatando a data
	 * @return void
	 */
	private function formatarData(): void{

		//Listando
		foreach( $this->atributos as $campo => $array )
			//Verificando
			if( $array[ 'tipo' ] == TIPO_DATA_CUSTOMIZADA && !is_null( $this->$campo ) && $this->$campo != '' )
				//Definindo
				$this->$campo	= Util::formatarData( $this->$campo, strlen( $this->$campo ) > 10 ? 'banco' : 'banco-sem-hora' );

	}

	/**
	 * Validando os campos
	 *
	 * @access private
	 * @param array $opcoes Opções
	 * @uses Validacao::validar Validando os campos
	 * @return void
	 */
	private function validar( $campos ): void{

		//Listando
		foreach( $this->atributos as $campo => $opcoes )
			//Verificando
			if( in_array( $campo, $campos ) )
				//Validando
				Validacao::validar( $this->$campo, $opcoes );

	}

	/**
	 * Serializando o JSON
	 *
	 * @access public
	 * @return mixed
	 */
	public function jsonSerialize(): mixed{

		//Listando
		foreach( $this->propriedades as $campo => $valor )
			//Verificando se o campo é nulo
			if( !is_null( $this->$campo ) )
				//Definindo os campos
				$campos[ $campo ]	= $valor;

		//Retornando
		return $campos ?? null;

    }

}