<?php
//Definindo o namespace
namespace App\Validacao;

//Definindo as classes usadas
use App\Excecao\Conteudo as ExcecaoConteudo;
use App\Mensagem\Mensagem;
use App\Util\Util;
use DateTime;

/**
 * Classe de validação
 *
 * @author Lucas Dantas <lucas@moveon.dev>
 * @package App
 * @subpackage Validacao
 * @version 1.0
 * @since 1.0 27/10/2023 15:01
 */
Class Validacao{

	/**
	 * Valor
	 *
	 * @access private
	 * @var mixed
	 */
	private static $valor;

	/**
	 * Opções do campo
	 *
	 * @access private
	 * @var array
	 */
	private static $opcoes;

	/**
	 * Validando os campos
	 *
	 * @access public
	 * @static
	 * @param mixed $valor Valor
	 * @param array $opcoes Opções dos campos
	 * @uses self::tamanho Validando o tamanho do campo
	 * @uses self::obrigatorio Validando se o campo é obrigatório
	 * @uses self::email Validando o campo de email
	 * @uses self::numerico Validando se o campo é numérico
	 * @uses self::coordenada Validando as coordenadas
	 * @uses self::url Validando os campos de URL
	 * @uses self::data Validando os campos de data
	 * @uses self::documentos Validando os documentos
	 */
	public static function validar( mixed $valor, array $opcoes ){

		//Definindo
		self::$valor	= $valor;
		self::$opcoes	= $opcoes;

		//Validando
		self::tamanho();
		self::obrigatorio();
		self::email();
		self::numerico();
		self::coordenada();
		self::url();
		self::data();
		self::documentos();

	}

	/**
	 * Validando o tamanho do campo
	 *
	 * @access public
	 * @static
	 * @throws ExcecaoConteudo
	 * @return void
	 */
	public static function tamanho(): void{

		//Verificando
		if( isset( self::$opcoes[ 'tamanho' ] ) && ( strlen( self::$valor ?? '' ) > self::$opcoes[ 'tamanho' ] ) )
			//Lançando a exceção
			throw new ExcecaoConteudo( sprintf( Mensagem::CAMPO_TAMANHO_INVALIDO, self::$opcoes[ 'nome' ], self::$opcoes[ 'tamanho' ] ), RESPOSTA_SOLICITACAO_INCORRETA );

	}

	/**
	 * Validando os campos obrigatórios
	 *
	 * @access public
	 * @static
	 * @throws ExcecaoConteudo
	 * @return void
	 */
	public static function obrigatorio(): void{

		//Verificando
		if( isset( self::$opcoes[ 'obrigatorio' ] ) && self::$opcoes[ 'obrigatorio' ] === TRUE && ( is_null( self::$valor ) || self::$valor == '' || empty( self::$valor ) ) )
			//Lançando a exceção
			throw new ExcecaoConteudo( sprintf( Mensagem::CAMPO_VAZIO, self::$opcoes[ 'nome' ] ), RESPOSTA_SOLICITACAO_INCORRETA );

	}

	/**
	 * Validando os campos de email
	 *
	 * @access public
	 * @static
	 * @throws ExcecaoConteudo
	 * @return void
	 */
	public static function email(): void{

		//Verificando
		if( ( self::$valor !== '' || !empty( self::$valor ) ) && self::$opcoes[ 'tipo' ] == TIPO_EMAIL && !filter_var( self::$valor, FILTER_VALIDATE_EMAIL ) )
			//Lançando a exceção
			throw new ExcecaoConteudo( sprintf( Mensagem::CAMPO_EMAIL_INVALIDO, self::$valor ), RESPOSTA_SOLICITACAO_INCORRETA );

	}

	/**
	 * Validando os campos numéricos
	 *
	 * @access public
	 * @static
	 * @throws ExcecaoConteudo
	 * @return void
	 */
	public static function numerico(): void{

		//Verificando
		if( !is_numeric( self::$valor ) && self::$valor != '' && self::$opcoes[ 'tipo' ] == TIPO_INTEIRO )
			//Lançando a exceção
			throw new ExcecaoConteudo( sprintf( Mensagem::CAMPO_NAO_INTEIRO, self::$opcoes[ 'nome' ] ), RESPOSTA_SOLICITACAO_INCORRETA );

	}

	/**
	 * Validando as coordenadas
	 *
	 * @access public
	 * @static
	 * @throws ExcecaoConteudo
	 * @return void
	 */
	public static function coordenada(): void{

		//Verificando
		if( ( self::$valor !== '' || !empty( self::$valor ) ) && self::$opcoes[ 'tipo' ] == TIPO_LATITUDE && !preg_match( '/^[-]?(([0-8]?[0-9])\.(\d+))|(90(\.0+)?)$/', self::$valor ) )
			//Lançando a exceção
			throw new ExcecaoConteudo( sprintf( Mensagem::CAMPO_LATITUDE_INVALIDO, self::$valor ), RESPOSTA_SOLICITACAO_INCORRETA );

		//Verificando
		if( ( self::$valor !== '' || !empty( self::$valor ) ) && self::$opcoes[ 'tipo' ] == TIPO_LONGITUDE && !preg_match( '/^[-]?((((1[0-7][0-9])|([0-9]?[0-9]))\.(\d+))|180(\.0+)?)$/', self::$valor ) )
			//Lançando a exceção
			throw new ExcecaoConteudo( sprintf( Mensagem::CAMPO_LONGITUDE_INVALIDO, self::$valor ), RESPOSTA_SOLICITACAO_INCORRETA );

	}

	/**
	 * Validando os campos de URL
	 *
	 * @access public
	 * @static
	 * @throws ExcecaoConteudo
	 * @return void
	 */
	public static function url(): void{

		//Verificando
		if( ( self::$valor !== '' || !empty( self::$valor ) ) && self::$opcoes[ 'tipo' ] == TIPO_URL && filter_var( self::$valor, FILTER_VALIDATE_URL ) === FALSE )
			//Lançando a exceção
			throw new ExcecaoConteudo( sprintf( Mensagem::CAMPO_URL_INVALIDA, self::$valor ), RESPOSTA_SOLICITACAO_INCORRETA );

	}

	/**
	 * Validando as datas
	 *
	 * @access public
	 * @static
	 * @uses self::validarData Validando a data
	 * @return void
	 */
	public static function data(): void{

		//Verificando
		if( ( self::$valor !== '' || !empty( self::$valor ) ) && self::$opcoes[ 'tipo' ] == TIPO_DATA )
			//Validando
			self::validarData( [ self::$valor ] );

	}

	/**
	 * Validando os documentos
	 *
	 * @access public
	 * @static
	 * @uses self::validarDocumento Validando os documentos
	 * @return void
	 */
	public static function documentos(): void{

		//Checking
		if( ( self::$valor !== '' || !empty( self::$valor ) ) && self::$opcoes[ 'tipo' ] == TIPO_DOCUMENTO_CNPJ )
			//Checking
			self::validarDocumento( self::$valor );
		else if( ( self::$valor !== '' || !empty( self::$valor ) ) && self::$opcoes[ 'tipo' ] == TIPO_DOCUMENTO_CPF )
			//Checking
			self::validarDocumento( self::$valor, 'CPF' );
	}

	/**
	 * Validando os campos vazios
	 *
	 * @access public
	 * @static
	 * @param array $array Array
	 * @throws ExcecaoConteudo
	 * @return void
	 */
	public static function validarCampoVazio( array $array ): void{

		//Listando
		foreach( $array as $descricao => $valor )
			//Verificando
			if( is_null( $valor ) || $valor == '' )
				//Lançando a exceção
				throw new ExcecaoConteudo( sprintf( Mensagem::CAMPO_VAZIO, $descricao ), RESPOSTA_SOLICITACAO_INCORRETA );

	}

	/**
	 * Validando os campos numéricos
	 *
	 * @access public
	 * @static
	 * @param array $array Campos do array
	 * @throws ExcecaoConteudo
	 * @return void
	 */
	public static function validarCampoNumerico( array $array ): void{

		//Listando
		foreach( $array as $descricao => $valor )
			//Verificando
			if( is_null( $valor ) || !is_numeric( $valor ) )
				//Lançando a exceção
				throw new ExcecaoConteudo( sprintf( Mensagem::CAMPO_NAO_INTEIRO, $descricao ), RESPOSTA_SOLICITACAO_INCORRETA );

	}

	/**
	 * Validando a data
	 *
	 * @access public
	 * @static
	 * @param array $array Datas
	 * @uses Util::formatDate Formating date
	 * @uses DateTime::createFromFormat Criando uma data a partir de um formato
	 * @uses DateTime::getLastErrors Pegando os erros
	 * @throws ExcecaoConteudo
	 * @return void
	 */
	public static function validarData( array $array ): void{

		//Listando
		foreach( $array as $data ){

			//Definindo
			$dataReal	= $data;

			//Definindo
    		$data		= DateTime::createFromFormat( 'd/m/Y', Util::formatDate( $data, 'pt-sem-hora' ) );

			//Definindo os erros
			$errors		= DateTime::getLastErrors();

			//Verificando
			if ( !empty( $errors[ 'warning_count' ] ) || count( $errors[ 'errors' ] ?? [] ) > 0 )
				//Lançando a exceção
				throw new ExcecaoConteudo( sprintf( Mensagem::CAMPO_DATA_INVALIDA, $dataReal ), RESPOSTA_SOLICITACAO_INCORRETA );

		}

	}

	/**
	 * Validando se é um array
	 *
	 * @access public
	 * @static
	 * @param ?array $campo Campo
	 * @param string $nome Nome do campo
	 * @throws ExcecaoConteudo
	 * @return void
	 */
	public static function validarArray( ?array $campo, string $nome ): void{

		//Verificando
		if( !is_array( $campo ) || count( $campo ) == 0 )
			//Lançando a exceção
			throw new ExcecaoConteudo( sprintf( Mensagem::CAMPO_ARRAY_INVALIDO, $nome ), RESPOSTA_SOLICITACAO_INCORRETA );

	}

	/**
	 * Validando o email
	 *
	 * @access public
	 * @static
	 * @param string $email E-mail
	 * @throws ExcecaoConteudo
	 * @return void
	 */
	public static function validarEmail( string $email ): void{

		//Verificando
		if( !filter_var( $email, FILTER_VALIDATE_EMAIL ) )
			//Lançando a exceção
			throw new ExcecaoConteudo( sprintf( Mensagem::CAMPO_EMAIL_INVALIDO, $email ), RESPOSTA_SOLICITACAO_INCORRETA );

	}

	/**
	 * Validando os documentos
	 *
	 * @access public
	 * @static
	 * @param string $documento Documento
	 * @param string $tipo Tipo
	 * @uses self::validarCNPJ Validando o CNPJ
	 * @uses self::validarCPF Validando o CPF
	 * @return void
	 */
	public static function validarDocumento( string $documento, string $tipo = 'CNPJ' ): void{

		//Checking
		if( $tipo == 'CNPJ' )
			//Validando o CNPJ
			self::validarCNPJ( $documento );
		else
			//Validando o CPF
			self::validarCPF( $documento );

	}

	/**
	 * Validando o CPF
	 *
	 * @access public
	 * @static
	 * @param string $cpf Document
	 * @throws ExcecaoConteudo
	 * @return void
	 */
	public static function validarCPF( string $cpf ): void{

		//Formatando
		$cpf	= preg_replace( '/\D/', '', $cpf );

		//Verificando
		if( strlen( $cpf ) != 11 || preg_match( '/(\d)\1{10}/', $cpf ) )
			//Lançando a exceção
			throw new ExcecaoConteudo( sprintf( Mensagem::COMPO_DOCUMENTO_CPF, $cpf ), RESPOSTA_SOLICITACAO_INCORRETA );

		//Definindo os digitos
		$digitos	= str_split( $cpf );

		//Definindo algumas variáveis
		$soma		= 0;

		//Listando
		for( $i = 0; $i < 9; $i++ )
			//Somando
			$soma	+= $digitos[ $i ] * ( 10 - $i );

		//Definindo algumas variáveis
		$resto			= $soma % 11;
		$primeiroDigito	= ( $resto < 2 ) ? 0 : ( 11 - $resto );

		if( $digitos[ 9 ] != $primeiroDigito )
			//Lançando a exceção
			throw new ExcecaoConteudo( sprintf( Mensagem::COMPO_DOCUMENTO_CPF, $cpf ), RESPOSTA_SOLICITACAO_INCORRETA );

		//Definindo algumas variáveis
		$soma	= 0;

		//Listando
		for( $i = 0; $i < 10; $i++ )
			//Somando
			$soma	+= $digitos[ $i ] * ( 11 - $i );

		//Definindo algumas variáveis
		$resto 			= $soma % 11;
		$segundoDigito	= ($resto < 2) ? 0 : (11 - $resto);

		//Verificando
		if( $digitos[ 10 ] != $segundoDigito )
			//Lançando a exceção
			throw new ExcecaoConteudo( sprintf( Mensagem::COMPO_DOCUMENTO_CPF, $cpf ), RESPOSTA_SOLICITACAO_INCORRETA );

	}

	/**
	 * Validando o CNPJ
	 *
	 * @access public
	 * @static
	 * @param string $cnpj CNPJ
	 * @throws ExcecaoConteudo
	 * @return void
	 */
	public static function validarCNPJ( string $cnpj ): void{

		//Formatando
		$cnpj	= preg_replace( '/[^0-9]/', '', $cnpj );

		//Verificando
		if( strlen( $cnpj ) != 14 )
			//Lançando a exceção
			throw new ExcecaoConteudo( sprintf( Mensagem::COMPO_DOCUMENTO_CNPJ, $cnpj ), RESPOSTA_SOLICITACAO_INCORRETA );

		//Definindo algumas variáveis
		$soma	= 0;
		$peso	= 5;

		//Listando
		for( $i = 0; $i < 12; $i++ ) {

			//Somando
			$soma	+= $cnpj[ $i ] * $peso;

			//Definindo o peso
			$peso	= ( $peso == 2 ) ? 9 : $peso - 1;

		}

		//Definindo algumas variáveis
		$resto 			= $soma % 11;
		$primeiroDigito	= ( $resto < 2 ) ? 0 : 11 - $resto;

		//Verificando
		if( $cnpj[ 12 ] != $primeiroDigito )
			//Lançando a exceção
			throw new ExcecaoConteudo( sprintf( Mensagem::COMPO_DOCUMENTO_CNPJ, $cnpj ), RESPOSTA_SOLICITACAO_INCORRETA );

		//Definindo algumas variáveis
		$soma	= 0;
		$peso	= 6;

		//Listando
		for( $i = 0; $i < 13; $i++ ){

			//Somando
			$soma 	+= $cnpj[ $i ] * $peso;

			//Definindo o peso
			$peso = ( $peso == 2 ) ? 9 : $peso - 1;
		}

		//Definindo algumas variáveis
		$resto 			= $soma % 11;
		$segundoDigito	= ( $resto < 2 ) ? 0 : 11 - $resto;

		//Verificando
		if( $cnpj[ 13 ] != $segundoDigito )
			//Lançando a exceção
			throw new ExcecaoConteudo( sprintf( Mensagem::COMPO_DOCUMENTO_CNPJ, $cnpj ), RESPOSTA_SOLICITACAO_INCORRETA );

	}

}