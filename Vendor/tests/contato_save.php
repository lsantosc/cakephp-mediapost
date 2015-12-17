<?php
/**
 * Salva um contato no @MediaPost
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
 * � recomentado que essa opera��o seja feita em lotes de no m�ximo 500 contatos por vez
 */

$arrContato = array();

/*
 * C�digo da lista onde vai ficar o contato
 */
$arrContato['lista'] = 506;

/*
 * C�digo do contato no sistema do cliente
 */
$arrContato['contato'][0]['uidcli'] = 1;
/*
 * C�digo do contato no @MediaPost. Usado para atualizar as informa��es do contato
 */
$arrContato['contato'][0]['cod'] = 0;
/*
 * Dados adicionais do contato. usar o m�todo /contato/campos para listar todos os campos dispon�veis
 */
$arrContato['contato'][0]['email'] = "teste".time();
$arrContato['contato'][0]['livre1'] = "campo livre 1 � �";
$arrContato['contato'][0]['livre2'] = "campo livre 2 � � ��o";

try { 
	$arrResult = $mapi->put("contato/salvar", $arrContato);
	echo "<pre>".print_r($arrResult, true)."</pre>";die;
} catch (MapiException $e){
	throw $e; 
}