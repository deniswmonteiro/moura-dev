<?php
/*
* Template's variables
*/

//Adicionando o topo
$template->addFile( 'INCLUDE_TOPO', DIR_HTML . '/include/topo.html' );

//Adicionando o cabeçalho
$template->addFile( 'INCLUDE_CABECALHO', DIR_HTML . '/include/cabecalho.html' );

//Adicionando o arquivo de include do javascript
$template->addFile( 'INCLUDE_JAVASCRIPT', DIR_HTML . '/include/javascript.html' );

//Definindo as variáveis
$template->URL      = URL;
$template->URL_CSS  = URL_CSS;
$template->URL_JS   = URL_JS;
$template->URL_IMG  = URL_IMG;