<?php
//Definindo o namespace
namespace App\Email;

//Definindo as classes usadas
use PHPMailer\PHPMailer\PHPMailer;

/**
 * Classe de email
 *
 * @author Lucas Dantas <lucas@moveon.dev>
 * @package App
 * @subpackage Email
 * @version 1.0
 * @since 1.0 28/10/2023 00:07
 */
Class Email{

	/**
	 * Método construtor da classe
	 *
	 * @access public
	 * @param ?string $destinatario Destinatário
	 * @param ?string $assunto Assunto
	 * @param ?string $mensagem Mensagem
	 * @uses $this->enviar Enviando
	 */
	public function __construct( private ?string $destinatario = null, private ?string $assunto = null, private ?string $mensagem = null ){

        //Definindo
		$email 				= new PHPMailer;

		//Configurando
		$email->isSMTP();
		$email->Host 		= EMAIL_SERVIDOR;
		$email->SMTPAuth 	= true;
		$email->Username 	= EMAIL;
		$email->Password 	= EMAIL_SENHA;
		$email->SMTPSecure 	= EMAIL_TIPO_SEGURANCA;
		$email->Port 		= EMAIL_PORTA;
		$email->CharSet		= 'UTF-8';
		$email->Sender		= EMAIL;
		$email->isHTML( true );
		$email->setFrom( EMAIL, NOME_PROJETO );
		$email->addAddress( $this->destinatario );

		//Definindo
		$email->Subject = $this->assunto;
		$email->Body    = $this->mensagem;

		//Enviando
		$email->send();

	}

}