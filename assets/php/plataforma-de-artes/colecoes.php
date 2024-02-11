<?php
/**
 * Exibindo a página da energia premiada
 */

//Definindo as classes
use App\Template\Template;

//Template
try{

	//Definindo o template
  	$template	= new Template( $pagina );

  	//Incluindo as variáveis
  	require( DIR . '/include/variaveis.php' );

}catch( Exception ){}

//Exibindo a página
$template->show();
