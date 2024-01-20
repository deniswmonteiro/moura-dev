<?php
//Definindo o namespace
namespace App\Util;

//Definindo as classes usadas
use App\Excecao\Conteudo as ExcecaoConteudo;
use App\Mensagem\Mensagem;
use IntlDateFormatter;
use DateTime;
use stdClass;

/**
 * Classe de funções úteis para o projeto
 *
 * @final
 * @author Lucas Dantas <lucas@moveon.dev>
 * @package App
 * @subpackage Util
 * @version 1.0
 * @since 1.0 27/10/2023 18:43
 */
Final Class Util{

	/*
	 * VERIFICAÇÕES
	 */

	/**
	 * Verificando o token CSRF
	 *
	 * @access public
	 * @static
	 * @param ?string $token Token
	 * @param bool $logout Token de logout
	 * @throws ExcecaoConteudo
	 * @return bool
	 */
	public static function verificarTokenCsrf( ?string $token = null, bool $logout = false ): bool{

		//Verificando e lançando a exceção
		$_SESSION ?? throw new ExcecaoConteudo( Mensagem::SESSAO_INVALIDA, RESPOSTA_SOLICITACAO_INCORRETA );

		//Verificando
		if( !$logout ){

			//Verificando e lançando a exceção
			$_SESSION[ 'csrf-token' ] ?? throw new ExcecaoConteudo( Mensagem::TOKEN_INVALIDO, RESPOSTA_SOLICITACAO_INCORRETA );

			//Verificando o token junto ao hash
			if( !hash_equals( $_SESSION[ 'csrf-token' ], $token ?? '' ) )
				//Lançando a exceção
				throw new ExcecaoConteudo( Mensagem::TOKEN_INVALIDO, RESPOSTA_SOLICITACAO_INCORRETA );

		}else{

			//Verificando e lançando a exceção
			$_SESSION[ 'csrf-token-logout' ] ?? throw new ExcecaoConteudo( Mensagem::TOKEN_INVALIDO, RESPOSTA_SOLICITACAO_INCORRETA );

			//Verificando o token junto ao hash
			if( !hash_equals( $_SESSION[ 'csrf-token-logout' ], $token ?? '' ) )
				//Lançando a exceção
				throw new ExcecaoConteudo( Mensagem::TOKEN_INVALIDO, RESPOSTA_SOLICITACAO_INCORRETA );

		}

		//Retornando
		return true;

	}

	/**
	 * Verificando a requisição
	 *
	 * @access public
	 * @static
	 * @param string $tipo Tipo
	 * @throws ExcecaoConteudo
	 * @return void
	 */
	public static function verificarRequisicao( string $tipo ): void{

		//Verificando
		if( $tipo !== $_SERVER[ 'REQUEST_METHOD' ] )
			//Lançando a exceção
			throw new ExcecaoConteudo( sprintf( Mensagem::TIPO_REQUISICAO, $_SERVER[ 'REQUEST_METHOD' ], $tipo ), RESPOSTA_SOLICITACAO_INCORRETA );

	}

	/**
	 * Verificando os erros de banco de dados
	 *
	 * @access public
	 * @static
	 * @param int $tipo Tipo do erro
	 * @return string
	 */
	public static function verificarTipoErroBandoDeDados( int $tipo = null ): string{

		//Retornando
		return match( $tipo ){

			//Relacionamento
			1451 	=> Mensagem::BD_ERRO_RELACIONAMENTO,
			//inserção e edição
			1452	=> Mensagem::BD_ERRO_INSERCAO_EDICAO,
			//Registros duplicados
			1062	=> Mensagem::BD_ERRO_DUPLICADO,
			//Padrão
			default	=> Mensagem::BD_ERRO_PADRAO

		};

	}

	/*
	 * FORMATAÇÕES
	 */

	/**
	 * Formatando os caracteres
	 *
	 * @access public
	 * @static
	 * @param mixed $valor Valor
	 * @return string
	 */
	public static function formatarCaracteres( mixed $valor ): mixed{

		//Retornando
		return !is_null( $valor ) ? stripslashes( htmlentities( $valor, ENT_QUOTES, 'UTF-8' ) ) : null;

	}

	/**
	 * Formatando caracteres para apenas números
	 *
	 * @access public
	 * @static
	 * @param string String
	 * @return string
	 */
	public static function formatarApenasNumeros( string $string ): string{

		//Retornando
		return preg_replace( '/\D+/', '', $string );

	}

	/**
	 * Formatando a data
	 *
	 * @access public
	 * @static
	 * @param ?string $data Data
	 * @param string $tipo Tipo
	 * @link https://unicode-org.github.io/icu/userguide/format_parse/datetime/#date-field-symbol-table Documentation
	 * @return mixed
	 */
	public static function formatarData( ?string $data, string $tipo = 'padrão' ): mixed{

		//Verificando
		if( !is_null( $data ) ){

			//Definindo
			$formato	= match( $tipo ){

				//Banco de dados
				'banco'				=> "yyyy-MM-dd HH:mm:ss",
				//Banco de dados sem hora
				'banco-sem-hora'	=> "yyyy-MM-dd",
				//Português sem hora
				'pt-sem-hora'		=> "dd/MM/yyyy",
				//Padrão
				default				=> "dd/MM/yyyy à's' HH'h'mm"

			};

			//Definindo a formatação
			$formatacao	= new IntlDateFormatter( 'pt_BR', IntlDateFormatter::FULL, IntlDateFormatter::NONE, 'America/Sao_Paulo', IntlDateFormatter::GREGORIAN, $formato );

			//Retornando
			return str_replace( [ ' - 00:00:00', ' - 00:00' ], '', $formatacao->format( new DateTime( str_replace( '/', '-', str_replace( '-', '', $data ) ) ) ) );

		}else
			//Retornando
			return null;

	}

	/**
	 * Formatando uma string para o formato de URL
	 * Geralmente usamos para criar um slug
	 *
	 * @access public
	 * @static
	 * @param string $string String
	 * @uses self::removerCaracterEspecial Removendo os caracteres especiais
	 */
	public static function formatarStringParaUrl( string $string ): string{

		//Retornando
		return strtolower( str_replace( ' ', '-', self::removerCaracterEspecial( html_entity_decode( $string, ENT_QUOTES, 'UTF-8' ) ) ) );

	}

	/*
	 * DEFINIÇÕES
	 */

	/**
	 * Definindo a descrição
	 *
	 * @access public
	 * @static
	 * @param ?string $texto Texto
	 * @param int $quantidadePalavras Quantidade de palavras
	 * @param bool $tag Remover as tags ou não
	 * @return string
	 */
	public static function definirDescricao( ?string $texto, int $quantidadePalavras = 30, bool $tag = true ): string{

		//Definindo
		$palavra	= explode( ' ', preg_replace( "/\r\n|\r|\n|\t/", '', strip_tags( $texto ) ) );

		//Definindo
		$quantidade	= count( $palavra );

		//Verificando
		if( $quantidade <= $quantidadePalavras )
			//Retornando
			return ( $tag ) ? rtrim( ltrim( trim( strip_tags( $texto ) ) ) ) : $texto;
		else{

			//Definindo
			$texto	= null;

			//Listando
			for( $i = 0; $i < $quantidadePalavras; $i++ )
				//Definindo
				$texto .= $palavra[ $i ] . ' ';

			//Definindo
			$texto	.= '...';

			//Retornando
			return ( $tag ) ? rtrim( ltrim( trim( strip_tags( $texto ) ) ) ) : $texto;

		}

	}

	/**
	 * Definindo o token CSRF
	 *
	 * @access public
	 * @static
	 * @param bool $logout Token de logout ou não
	 * @return string
	 */
	public static function definirTokenCsrf( bool $logout = false ): string{

		//Definindo
		$token	= bin2hex( random_bytes( 32 ) );

		//Verificando
		if( $logout )
			//Definindo
			$_SESSION[ 'csrf-token-logout' ]	= $token;
		else
			//Definindo
			$_SESSION[ 'csrf-token' ]			= $token;

		//Retornando
		return $token;

	}

	/**
	 * Definindo a ordem dos registros para o banco de dados
	 *
	 * @access public
	 * @static
	 * @param string $ordem Ordem
	 * @return string
	 */
	public static function definirOrdemBancoDeDados( string $ordem = null ): string{

		//Retornando
		return match( $ordem ){

			//Nome
			'nome'			=> 'name',
			//Quantidade de visualizações
			'visualizações'	=> 'view DESC',
			//Randômico
			'randômico'		=> 'RAND()',
			//Padrão
			default			=> 'id DESC'

		};

	}

	/**
	 * Definindo os métodos para checagem única
	 *
	 * @access public
	 * @static
	 * @param string $campo Campo
	 * @return object
	 */
	public static function definirMetodoUnico( string $campo ): object{

		//Definindo o objeto
		$objeto			= new stdclass();

		//Retornando
		$objeto->metodo	= match( $campo ){

			//Email
			'email'		=> 'verificarExistenciaPorEmail',
			//Documento
			'document'	=> 'verificarExistenciaPorDocumento',
			//Padrãi
			default		=> 'verificarExistenciaPorNome'

		};

		//Retornando
		$objeto->excecao	= match( $campo ){

			//Email
			'email'		=> Mensagem::JA_EXISTE_POR_EMAIL,
			//Documento
			'document'	=> Mensagem::JA_EXISTE_POR_DOCUMENTO,
			//Padrão
			default		=> Mensagem::JA_EXISTE_POR_NOME

		};

		//Retornando
		return $objeto;

	}

	/**
	 * Definindo a senha
	 *
	 * @access public
	 * @static
	 * @param int $quantidade Quantidade de caracteres
	 * @return object
	 */
	public static function definirSenha( int $quantidade = 15 ): object{

		//Definindo o objeto
		$objeto		= new stdclass();

		//Definindo os caracteres para compor a senha
		$caracteres	= 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789!@#$%^&*()-_=+[]{}|;:,.<>?';

		//Iniciando a criação
		$senha 		= '';

		//Definindo o tamanho
    	$tamanho 	= mb_strlen( $caracteres, '8bit' ) - 1;

		//Listando o tamanho para geração da senha
    	foreach( range( 1, $quantidade ) as $i )
			//Concatenando a senha e criando
        	$senha	.= $caracteres[ random_int( 0, $tamanho ) ];

		//Definindo a senha
		$objeto->password	= $senha;

		//Definindo o hash da senha
		$objeto->hash		= password_hash( $senha, PASSWORD_BCRYPT, [ 'cost' => 12 ] );

		//Retornando
		return $objeto;

	}

	/*
	 * REMOÇÕES
	 */

	/**
	 * Removendo os caracteres especiais
	 *
	 * @access public
	 * @static
	 * @param string $string String
	 * @param bool $antigoMetodo Antigo método de remoção
	 * @return string
	 */
	public static function removerCaracterEspecial( string $string, bool $antigoMetodo = false ): string{

		//Verificando
		if( !$antigoMetodo ){

			//Removendo os acentos
			$string	= preg_replace( '/[áàãâä]/u', 'a', $string );
			$string = preg_replace( '/[éèêë]/u', 'e', $string );
			$string = preg_replace( '/[íìîï]/u', 'i', $string );
			$string = preg_replace( '/[óòõôö]/u', 'o', $string );
			$string = preg_replace( '/[úùûü]/u', 'u', $string );
			$string	= str_replace( ' ', '_', $string );

			//Retornando
			return preg_replace( '/[^a-zA-Z0-9_]/', '', $string );

		}else{

			//Definindo
			$acentos	= [ "Á","É","Í","Ó","Ú","Â","Ê","Î","Ô","Û","Ã","Ñ","Õ","Ä","Ë","Ï","Ö","Ü","À","È","Ì","Ò","Ù","á","é","í","ó","ú","â","ê","î","ô","û","ã","ñ","õ","ä","ë","ï","ö","ü","à","è","ì","ò","ù",".",",",":",";","...","ç","%","?","/","\\","”","“","'","!","@","#","$","&","*","(",")","+","=","{","}","[","]","|","<",">","\"","&ordf;","&ordm;","&deg;","‘","‘","&raquo;","","ª","º","»","´","Ç","’","&ndash;","&nbsp;","–","″","°","~","³", "`", "^", "---", "®", "©" ];
			$semAcentos	= [ "a","e","i","o","u","a","e","i","o","u","a","n","o","a","e","i","o","u","a","e","i","o","u","a","e","i","o","u","a","e","i","o","u","a","n","o","a","e","i","o","u","a","e","i","o","u","","","","","","c","_porcento","","","","","","","","","","","","","","","","","","","","","","","","","","","","","","","","","","","","c","","","","","","","","","", "", "-", "","" ];

			//Retornando
			return str_replace( $acentos, $semAcentos, $string );

		}

	}

}