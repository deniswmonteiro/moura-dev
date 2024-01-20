<?php
//Definindo o namespace
namespace App\Modelo;

//Definindo as classes usadas
use App\Modelo\Modelo;

/**
 * Classe dos cursos
 * Herdada da classe pai Modelo
 *
 * @author Lucas Dantas <lucas@moveon.dev>
 * @package App
 * @subpackage Modelo
 * @version 1.1
 * @since 1.0 31/10/2023 09:34
 */
Class Curso Extends Modelo{

	/**
	 * Configurações
	 *
	 * @access public
	 * @var array
	 */
    public $configuracao	= [
								'tipo'	=> 'BDR',
								'nome'	=> 'Curso',
								'banco'	=> [ 'tabela'	=> 'course' ],
								'relacionamento'	=> [ [
									'classe'	=> 'Categoria\Curso\Categoria',
									'campo'		=> 'Categoria',
									'tipo'		=> 'BDR' ], [
										'classe'	=> 'Aula',
										'campo'		=> 'Aula',
										'tipo'		=> 'BDR' ] ] ];

	/**
	 * Atributos
	 *
	 * @access public
	 * @var array
	 */
	public $atributos	= [

		'id'    	=> [ 'nome' => 'Identificador', 'tipo' => TIPO_INTEIRO, 'formatar' => false ],
		'category'	=> [ 'nome' => 'Categoria', 'tipo' => TIPO_INTEIRO, 'formatar' => false ],
        'name'  	=> [ 'nome' => 'Nome', 'tipo' => TIPO_STRING, 'formatar' => true ],
		'slug'  	=> [ 'nome' => 'Nome', 'tipo' => TIPO_STRING, 'formatar' => false ],
		'view'  	=> [ 'nome' => 'Nome', 'tipo' => TIPO_INTEIRO, 'formatar' => false ],
		'average'  	=> [ 'nome' => 'Nome', 'tipo' => TIPO_DECIMAL, 'formatar' => false ],
		'image'  	=> [ 'nome' => 'Nome', 'tipo' => TIPO_STRING, 'formatar' => false ] ];

}