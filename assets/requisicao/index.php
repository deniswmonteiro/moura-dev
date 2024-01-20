<?php
/*
 * Arquivo de requisição
 * Todas as chamadas vão passar por esse arquivo
 */

//Definindo as classes usadas
use App\Pagina\Pagina;
use Dotenv\Dotenv;

//Iniciando a sessão
session_start();

//Definindo o timezone
date_default_timezone_set( 'America/Recife' );

//Definindo o local
setlocale( LC_TIME, 'ptBR', 'pt_BR', 'pt_BR.utf-8', 'portuguese' );

//Autoload dos arquivo
include( '../../vendor/autoload.php' );

//Definindo as variáveis de ambiente
Dotenv::createImmutable( '../..' )->load();

//Incluindo o arquivo principal
include( '../../include/principal.php' );

//Verificando se é produção para mostrar os erros ou não
if( PRODUCAO )
	//Escondendo os erros em caso de produção
	error_reporting( 0 );

//Iniciando a página
new Pagina( $_GET );