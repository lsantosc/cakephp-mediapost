<?php
/**
 * Cria uma lista
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
 * Nome da lista
 */
$arrLista['nome'] = 'Minha lista de contatos';

try { 
	$arrResult = $mapi->post("lista/salvar", $arrLista);
	echo "<pre>".print_r($arrResult, true)."</pre>";die;
} catch (MapiException $e){
	throw $e; 
}