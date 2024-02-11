<?php
/**
 * Exibindo a página da plataforma de artes
 */

//Definindo as classes
use App\Template\Template;

//Template
try{

	//Definindo o template
  	$template	= new Template( $pagina );

  	//Incluindo as variáveis
  	require( DIR . '/include/variaveis.php' );

	//Adicionando a busca
	$template->addFile( 'INCLUDE_BUSCA', DIR_HTML . '/include/busca.html' );

}catch( Exception ){}

//Exibindo a página
$template->show();
