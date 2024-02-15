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

	//Incluindo as variáveis
	require( DIR . '/include/variaveis.php' );

	//Adicionando os filtros
	$template->addFile( 'INCLUDE_ASIDE', DIR_HTML . '/include/aside-filter.html' );
	
	// Adicionando modal
	$template->addFile( 'INCLUDE_MODAL_DETALHAMENTO_CLIENTES', DIR_HTML . '/include/modal-detalhamento-clientes.html' );
}catch( Exception ){}

//Exibindo a página
$template->show();