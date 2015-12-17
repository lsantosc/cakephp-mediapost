<?php
/**
 * Agenda o envio de uma mensagem
 *
 * @copyright 2011 - MT4 Tecnologia
 * @author Diego Matos <diego@mt4.com.br>
 * @category MT4
 * @package 
 * @subpackage 
 * @since 01/06/2011
 */

require_once 'conf.php';

/*
 * Data hora do envio. Formato SQL
 * Caso n�o seja informado o sistema assumir� a data atual como data do envio 
 */
$arrEnvio['datahora_envio'] = "2011-01-01 10:00:00";
/*
 * C�digos das listas que devem ser enviadas. Obrigat�rio.
 */
$arrEnvio['lista'][] = 749;

/*
 * Filtros da lista. Devem ser usados os campos do cadastro do contato.
 * Essa informa��o pode ser encontrada em URL_API/contato/campos
 */
$arrEnvio['filtro']['livre1'][] = "valor1";
$arrEnvio['filtro']['livre1'][] = "valor2";
$arrEnvio['filtro']['livre1'][] = "valor3";


$arrEnvio['filtro']['livre2'][] = "valor1";
$arrEnvio['filtro']['livre2'][] = "valor2";


$arrEnvio['filtro']['livre3'] = "valor1";
/*
 * C�digo da mensagem que ser� enviada
 */
$cod_mensagem = 398;

try { 
	$arrResult = $mapi->put("envio/cod/".$cod_mensagem, $arrEnvio);
	echo "<pre>".print_r($arrResult, true)."</pre>";die;
} catch (MapiException $e){
	throw $e; 
}