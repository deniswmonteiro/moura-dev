<?php
/**
 * Exibindo a página de sell out
 */

//Definindo as classes
use App\Template\Template;

//Template
try{

	//Definindo o template
  	$template	= new Template( $pagina );

	//Adicionando os filtros
	$template->addFile( 'INCLUDE_ASIDE', DIR_HTML . '/include/aside-filter.html' );

  	//Incluindo as variáveis
  	require( DIR . '/include/variaveis.php' );


}catch( Exception ){}

//Exibindo a página
$template->show();