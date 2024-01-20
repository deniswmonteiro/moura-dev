<?php
//Definindo o namespace
namespace App\Paginacao;

/**
 * Classe de paginação
 *
 * @author Rafael Cardoso <rafus0@hotmail.com>
 * @author Lucas Dantas <lucas@moveon.dev>
 * @package App
 * @subpackage Paginacao
 * @version 1.0
 * @since 1.0 03/11/2023 15:25
 */
Class Paginacao{

    /**
	 * Propriedades
	 *
	 * @access public
	 * @var array
	 */
	public array $propriedades  = [];

	/**
	 * Método construtor da classe
	 *
	 * @access public
	 * @param int $offset Offset
	 * @param int $registrosPorPagina Records per page
	 * @param int $quantidade Quantidade de conteúdos
	 * @param string $diretorio Diretório
	 * @param string $termo Termo de busca
     * @uses $this->paginar Paginando
	 */
	public function __construct( ?int $offset = 0, ?int $registrosPorPagina = REGISTROS_POR_PAGINA, ?int $quantidade = 0, ?string $diretorio = null, ?string $termo = null ){

		//Definindo os valores
		$this->offset			    = str_pad( ceil( $offset / $registrosPorPagina) + 1, 2, '0', STR_PAD_LEFT );
		$this->registrosPorPagina   = $registrosPorPagina;
		$this->quantidade			= $quantidade;
		$this->diretorio		    = $diretorio;
		$this->termo		        = !is_null( $termo ) ? '?' . $termo : null;
		$this->proximaPagina	    = $this->offset + 1;
		$this->paginaAnterior		= $this->offset - 1;

		//Verificando
		if( $this->offset > 8 )
			//Verificando
			if ( $this->offset > 1 )
				//Verificando
				if( $this->offset > 4 )
					//Definindo
					$this->primeiraPagina	= $this->offset - 4;
				else
					//Definindo
					$this->primeiraPagina	= 1;
			else
				//Definindo
				$this->primeiraPagina	= $this->offset;
		else
			//Definindo
			$this->primeiraPagina	= 1;

		//Paginando
		$this->paginar();

	}

    /**
	 * Atribuições
	 *
	 * @access public
	 * @param string $chave Chave do campo
	 * @param mixed $campo Valor do campo
	 * @return void
	 */
	public function __set( string $chave, mixed $campo ): void{

		//Definindo
        $this->propriedades[ $chave ]	= $campo;

    }

	/**
	 * Obtendo os atributos
	 *
	 * @access public
	 * @param string $chave Chave do campo
	 * @return mixed
	 */
	public function __get( string $chave ): mixed{

		//Retornando
        return $this->propriedades[ $chave ];

    }

	/**
	 * paginando
	 *
	 * @access public
	 * @return string
	 */
	public function paginar(){

		//Definindo
		$numeroDaPagina	= 0;

		//Listando
		while( $this->quantidade > 0 ){

		    //Incrementando
			$numeroDaPagina++;

			//Definindo
		    $this->quantidade	= $this->quantidade - $this->registrosPorPagina;

		}

		//Definindo
		$this->quantidadeDePaginas	= $numeroDaPagina;

		//Verificando
		if( $this->quantidade > 8 && ( $this->offset - $this->primeiraPagina ) > 2 )
			//Definindo
			$this->primeiraPagina    = ( $this->offset - $this->primeiraPagina ) - 2;

        //Definindo
		$ultimaPagina	= ( ( $this->primeiraPagina + 8 ) < $numeroDaPagina ) ? $this->primeiraPagina + 8 : $numeroDaPagina;

		//Definindo
        $html		    = null;

        //Verificando
		if( $this->offset != 1 )
            //Definindo
            $html   .= '<a href="' . URL . '/' . $this->diretorio . '/pagina/' . $this->paginaAnterior . $this->termo . '"><svg><use xlink:href="' . URL_IMG . '/svg/svg.svg#arrow-white-left"></use></svg></a>';

        //Definindo
        $html   .= '<div class="pagination-namber"><span class="active">' . $this->offset . '</span><span>/' . $this->quantidadeDePaginas . '</span></div>';

        //Verificando
		if( ( $ultimaPagina != $this->offset ) && ( $numeroDaPagina != 0 ) )
		    //Definindo a próxima página
		    $html	.= '<a href="' . URL . '/' . $this->diretorio . '/pagina/' . $this->proximaPagina . $this->termo . '"><svg><use xlink:href="' . URL_IMG . '/svg/svg.svg#arrow-white-right"></use></svg></a>';

		//Definindo
		$this->paginacao    = $html;

	}

}