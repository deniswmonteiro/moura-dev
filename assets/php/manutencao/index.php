<?php
/**
 * Exibindo a página de manutenção
 */

//Definindo as classes
use App\Template\Template;

//Template
try{

	//Definindo o template
	$template	= new Template( $pagina );

}catch( Exception ){}

//Definindo algumas variáveis
$template->URL_CSS	= URL_CSS;
$template->URL_IMG	= URL_IMG;
$template->URL		  = URL;

//Exibindo a página
$template->show();