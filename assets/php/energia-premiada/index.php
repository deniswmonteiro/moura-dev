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

	//Adicionando os filtros
	$template->addFile( 'INCLUDE_ASIDE', DIR_HTML . '/include/aside-filter.html' );

}catch( Exception ){}

//Exibindo a página
$template->show();