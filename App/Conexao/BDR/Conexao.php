<?php
//Defining the namespace
namespace App\Conexao\BDR;

//Definindo as classes usadas
use PDO;

/**
 * Classe de conexão
 * Conexão para Banco de Dados Relacional
 *
 * @author Lucas Dantas <lucas@moveon.dev>
 * @package Conexao
 * @subpackage BDR
 * @version 1.0
 * @since 1.0 27/10/2023 19:54
 */
Class Conexao{

	/**
	 * Instância
	 *
	 * @access private
	 * @var PDO
	 */
	private static $instancia	= null;

	/**
	 * Método construtor da classe
	 *
	 * @access public
	 * @param ?object $conexao Conexão
	 */
	public function __construct( public ?object $conexao = null ){


		//Definindo
		$this->conexao	= new PDO( 'mysql:host=' . BD_ENDERECO . ';port=' . BD_PORTA . ';dbname=' . BD_BASE . ';charset=utf8', BD_USUARIO, BD_SENHA, [ PDO::MYSQL_ATTR_INIT_COMMAND => 'SET CHARACTER SET utf8', PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, PDO::MYSQL_ATTR_USE_BUFFERED_QUERY => true ] );

	}

	/**
	 * Obtendo a instância
	 *
	 * @access public
	 * @static
	 * @return Conexao
	 */
	public static function getInstancia(): Conexao{

		//Verificando
		if( is_null( self::$instancia ) )
			//Definindo a instância
			self::$instancia	= new self();

		//Retornando
		return self::$instancia;

	}

}