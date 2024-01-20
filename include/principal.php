<?php
//Definindo o diretório padrão
define( 'DIR', $_SERVER[ 'DIR' ] );

//Definindo a URL padrão
define( 'URL', $_SERVER[ 'URL' ] );

//Definindo se o projeto está em produção ou não
define( 'PRODUCAO', $_SERVER[ 'PRODUCAO' ] );

//Definindo o endereço do banco de dados
define( 'BD_ENDERECO', $_SERVER[ 'BD_ENDERECO' ] );
//Definindo a base do banco de dados
define( 'BD_BASE', $_SERVER[ 'BD_BASE' ] );
//Definindo a porta do banco de dados
define( 'BD_PORTA', $_SERVER[ 'BD_PORTA' ] );
//Definindo o usuário do banco de dados
define( 'BD_USUARIO', $_SERVER[ 'BD_USUARIO' ] );
//Definindo o endereço do banco de dados
define( 'BD_SENHA', $_SERVER[ 'BD_SENHA' ] );

//Definindo o servidor do email
define( 'EMAIL_SERVIDOR', $_SERVER[ 'EMAIL_SERVIDOR' ] );
//Definindo a porta do servidor de email
define( 'EMAIL_PORTA', $_SERVER[ 'EMAIL_PORTA' ] );
//Definindo o tipo de segurança do servidor de email
define( 'EMAIL_TIPO_SEGURANCA', $_SERVER[ 'EMAIL_TIPO_SEGURANCA' ] );
//Definindo o email
define( 'EMAIL', $_SERVER[ 'EMAIL' ] );
//Definindo a senha do email
define( 'EMAIL_SENHA', $_SERVER[ 'EMAIL_SENHA' ] );

//Definindo o diretório dos assets
define( 'DIR_ASSETS', DIR . '/assets' );
//Definindo o endereço das requições em ajax
define( 'DIR_SUBMIT', DIR_ASSETS . '/submit' );
//Definindo o diretório dos arquivos PHP
define( 'DIR_PHP', DIR_ASSETS . '/php' );
//Definindo o diretório dos arquivos HTML
define( 'DIR_HTML', DIR_ASSETS . '/html' );
//Definindo o diretório dos arquivos de estilo
define( 'DIR_CSS', DIR_ASSETS . '/css' );
//Definindo o diretório do repositório
define( 'DIR_REPOSITORIO', DIR . '/repositorio' );

//Definindo a URL dos assets
define( 'URL_ASSETS', URL . '/assets' );
//Definindo a URL das requisiçõesm em ajax
define( 'URL_SUBMIT', URL_ASSETS . '/submit' );
//Definindo a URL dos arquivos JS
define( 'URL_JS', URL_ASSETS . '/js' );
//Definindo a URL dos arquivos de estilo
define( 'URL_CSS', URL_ASSETS . '/css' );
//Definindo a URL dos arquivos de imagem
define( 'URL_IMG', URL_ASSETS . '/images' );
//Definindo a URL dos arquivos de ícones
define( 'URL_ICONE', URL_ASSETS . '/icons' );
//Definindo a URL da imagem de compartilhamento
define( 'URL_IMG_COMPARTILHAMENTO', URL_IMG . '/compartilhamento.jpg' );

//Definindo o código de resposta como sucesso
define( 'RESPOSTA_OK', 200 );
//Definindo o código de resposta como solicitação incorreta
define( 'RESPOSTA_SOLICITACAO_INCORRETA', 400 );
//Definindo o código de resposta como não encontrado
define( 'RESPOSTA_NAO_ENCONTRADO', 404 );
//Definindo o código de resposta como não autorizado
define( 'RESPOSTA_NAO_AUTORIZADO', 401 );
//Definindo o código de resposta como duplicado
define( 'RESPOSTA_DUPLICADO', 409 );
//Definindo o código de resposta como proibido
define( 'RESPOSTA_PROIBIDO', 403 );

//Definindo o tipo como inteiro
define( 'TIPO_INTEIRO', 1 );
//Definindo o tipo como string
define( 'TIPO_STRING', 2 );
//Definindo o tipo como email
define( 'TIPO_EMAIL', 3 );
//Definindo o tipo como booleano
define( 'TIPO_BOOLEANO', 4 );
//Definindo o tipo como data
define( 'TIPO_DATA', 5 );
//Definindo o tipo como URL
define( 'TIPO_URL', 6 );
//Definindo o tipo como longitude
define( 'TIPO_LONGITUDE', 7 );
//Definindo o tipo como latitude
define( 'TIPO_LATITUDE', 8 );
//Definindo o tipo como array
define( 'TIPO_ARRAY', 9 );
//Definindo o tipo como JSON
define( 'TIPO_JSON', 10 );
//Definindo o tipo como objeto
define( 'TIPO_OBJETO', 11 );
//Definindo o tipo como texto
define( 'TIPO_TEXTO', 12 );
//Definindo o tipo como CNPJ
define( 'TIPO_DOCUMENTO_CNPJ', 13 );
//Definindo o tipo como CPF
define( 'TIPO_DOCUMENTO_CPF', 14 );
//Definindo o tipo como data customizada
define( 'TIPO_DATA_CUSTOMIZADA', 15 );
//Definindo o tipo como decimal
define( 'TIPO_DECIMAL', 16 );

//Definindo o parâmetro como inteiro
define( 'PARAMETRO_INTEIRO', 1 );
//Definindo o parâmetro como string
define( 'PARAMETRO_STRING', 2 );
//Definindo o parâmetro como booleano
define( 'PARAMETRO_BOOLEANO', 5 );

//Definindo o nome do projeto
define( 'NOME_PROJETO', 'Plataforma de Trade' );
//Definindo o nome do sistema
define( 'NOME_SISTEMA', 'Controle' );
//Definindo a URL do sistema
define( 'URL_SISTEMA', 'https://controle.io' );