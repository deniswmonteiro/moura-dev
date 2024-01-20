<?php
//Definindo o namespace
namespace App\Mensagem;

/**
 * Mensagens
 *
 * @final
 * @author Lucas Dantas <lucas@moveon.dev>
 * @package App
 * @subpackage Mensagem
 * @version 1.0
 * @since 1.0 27/10/2023 17:43
 */
Final Class Mensagem{

	//Definindo as mensagens para validação
	const CAMPO_VAZIO   		        = 'O campo("%s") está vazio. Por favor, preencha novamente.';
	const CAMPO_TAMANHO_INVALIDO        = 'O campo("%s") está com o tamanho inválido. Não pode ultrapassar("%s") caractere(s).';
	const CAMPO_EMAIL_INVALIDO			= 'O e-mail("%s") está inválido.';
	const CAMPO_NAO_INTEIRO			    = 'O campo("%s") não é numérico.';
	const CAMPO_ARRAY_INVALIDO			= 'O campo("%s") não é um array válido.';
	const CAMPO_LATITUDE_INVALIDO		= 'A latitude("%s") está inválida.';
	const CAMPO_LONGITUDE_INVALIDO		= 'A longitude("%s") está inválida.';
	const CAMPO_URL_INVALIDA		    = 'A URL("%s") está inválida.';
	const CAMPO_DATA_INVALIDA			= 'A data("%s") está inválida.';
	const COMPO_DOCUMENTO_CNPJ	        = 'O CNPJ("%s") está inválido.';
	const COMPO_DOCUMENTO_CPF	        = 'O CPF("%s") está inválido.';

	//Definindo as mensagens para os registros
	const TIPO_REQUISICAO			    = 'O método não aceita esse tipo de requisição (%s). Somente requisições do tipo (%s).';
	const MODELO_INVALIDO			    = 'Os campos passados para o modelo não estão válidos. Por favor, entre em contato com o administrador do sistema.';
	const FALTANDO_MODELO			    = 'O modelo("%s") passado é inválido. Por favor, entre em contato com o administrador do sistema.';
	const FALTANDO_PARAMETROS           = 'O parâmetro("%s") passado é inválido. Por favor, entre em contato com o administrador do sistema.';
	const TOKEN_INVALIDO			    = 'O token passado não é válido. Por favor, entre em contato com o administrador do sistema.';
	const SESSAO_INVALIDA		        = 'A sessão do usuário não é válida. Por favor, entre em contato com o administrador do sistema.';
	const NENHUM_REGISTRO_ENCONTRADO    = 'Nenhum conteúdo(%s) encontrado.';
	const CONTEUDO_NAO_EXISTE           = 'O conteúdo("%s") não foi encontrado.';
	const JA_EXISTE_POR_NOME			= 'Já existe um conteúdo com este nome.';
	const JA_EXISTE_POR_DOCUMENTO       = 'Já existe um conteúdo com este CPF/CNPJ.';
	const JA_EXISTE_POR_EMAIL		    = 'Já existe um conteúdo com este email.';
	const NAO_EXISTE_POR_DOCUMENTO      = 'O CPF/CNPJ(%s) não existe.';
	const SEM_AUTORIZACAO				= 'Você não está autorizado a executar essa ação! Por favor, entre em contato com o administrador do sistema.';

	//Definindo as mensagens para o repositório
	const INSERIR				        = 'Não foi possível realizar a inserção do conteúdo. Por favor, tente novamente mais tarde.';
	const VERIFICAR    			        = 'Não foi possível realizar a verificação de existência do conteúdo. Por favor, tente novamente mais tarde.';
	const PROCURAR    			        = 'Não foi possível realizar a procura do conteúdo. Por favor, tente novamente mais tarde.';
	const LISTAR	    			    = 'Não foi possível realizar a listagem dos conteúdos. Por favor, tente novamente mais tarde.';
	const CONTAR	    			    = 'Não foi possível realizar a contagem dos conteúdos. Por favor, tente novamente mais tarde.';
	const REMOVER	    			    = 'Não foi possível realizar a remoção do conteúdo. Por favor, tente novamente mais tarde.';

	//Definindo as mensagens para os erros de banco de dados
	const BD_ERRO_RELACIONAMENTO		= 'Existem relacionamentos entre os seus módulos.';
	const BD_ERRO_INSERCAO_EDICAO		= 'Não existe o módulo pai para realizar a inserção/edição.';
	const BD_ERRO_DUPLICADO				= 'Já existe uma entrada cadastrada com o valor passado.';
	const BD_ERRO_PADRAO				= 'Algum erro foi encontrado. Por favor, entre em contato com o administrador do sistema.';

}