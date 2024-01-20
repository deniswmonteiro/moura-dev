<?php
//Definindo o namespace
namespace App\Fabrica;

//Definindo as classes usadas
use App\Repositorio\BDR\Repositorio as Repositorio;
use App\Controlador\Controlador as Controlador;
use App\Excecao\Conteudo as ExcecaoConteudo;
use App\Mensagem\Mensagem;

/**
 * Classe da fábrica.
 * Todas as instâncias dos controladores e repositórios são passadas por aqui
 *
 * @author Lucas Dantas <lucas@mvoeon.dev>
 * @package App
 * @subpackage Fabrica
 * @version 1.0
 * @since 1.0 28/10/2023 00:42
 */
Class Fabrica{

	/**
	 * Obtendo o tipo
	 *
	 * @access public
	 * @static
	 * @param string $modelo Nome do modelo
	 * @param string $implementacao Tipo de implementação
	 * @param string $tipo Tipo(controlador ou repositório)
	 * @uses App\Controlador\Controlador::getInstancia Obtendo a instância do controlador
	 * @uses App\Repositorio\Repositorio::getInstancia Obtendo a instância do repositório
	 * @return mixed
	 */
	public static function getTipo( string $modelo = null, string $implementacao = 'BDR', string $tipo = 'controlador' ): Controlador|Repositorio{

        //Definindo os nomes das classes
        $repositorio	= 'App\\Repositorio\\' . $implementacao . '\\' . $modelo;
        $controlador	= 'App\\Controlador\\' . $modelo;
        $modelo			= 'App\\Modelo\\' . $modelo;

		//Verificando
        if( !class_exists( $modelo ) )
            //Lançando a exceção
            throw new ExcecaoConteudo( sprintf( Mensagem::FALTANDO_MODELO, $modelo ), RESPOSTA_SOLICITACAO_INCORRETA );

        //Verificando
        if( !class_exists( $controlador ) )
			//Definindo
            $controlador	= 'App\\Controlador\\Principal\\Controlador';

        //Verificando
        if( !class_exists( $repositorio ) )
            //Definindo
            $repositorio	= 'App\\Repositorio\\' . $implementacao . '\\Principal\\Repositorio';

		//Retornando
		return ( $tipo == 'controlador' )  ? new $controlador( $repositorio::getInstancia( $repositorio, $modelo ), $modelo ) : $repositorio::getInstancia( $repositorio, $modelo );

	}

}