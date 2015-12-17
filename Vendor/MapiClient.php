<?
require_once 'MapiException.php';
if (!function_exists('curl_init')) {
  throw new Exception('MapiClient needs the CURL PHP extension.');
}
if (!function_exists('json_decode')) {
  throw new Exception('MapiClient needs the JSON PHP extension.');
}

require_once 'oauth/OAuth.php';

/**
 * Class MapiClient
 * Acesso a API Client do Media Post
 */
class MapiClient {

	const API_URL_BASE = "https://api.mediapost.com.br";
	private $_consumerKey = null;
	private $_consumerSecret = null;
	private $_token = null;
	private $_tokenSecret = null;
	private $_custom_urlbase = null;


	/**
	 * MapiClient constructor.
	 * @param bool $_consumerKey
	 * @param bool $_consumerSecret
	 * @param bool $_token
	 * @param bool $_tokenSecret
	 */
	public function __construct($_consumerKey = false, $_consumerSecret = false, $_token = false, $_tokenSecret = false){
	 	if($_consumerKey){
	 		$this->setConsumerKey($_consumerKey);
	 	}
	 	
		if($_consumerSecret){
	 		$this->setConsumerSecret($_consumerSecret);
	 	}
	 	
		if($_token){
	 		$this->setToken($_token);
	 	}
	 	
		if($_tokenSecret){
	 		$this->setTokenSecret($_tokenSecret);
	 	}
	}

	/**
	 * Altera a URL base da API
	 * @param $url
	 */
	public function setUrlBase($url){
	 	$this->_custom_urlbase = $url;
	}

	/**
	 * Retorna a URL base da API
	 * @return null|string
	 */
	public function getUrlBase(){
		if($this->_custom_urlbase){
			return $this->_custom_urlbase;
		} else {
			return self::API_URL_BASE;
		}
	}

	/**
	 * Define um ConsumerKey
	 * @param $_consumerKey
	 */
	public function setConsumerKey($_consumerKey){
	 	$this->_consumerKey = $_consumerKey;
	}

	/**
	 * Define um ConsumerSecret
	 * @param $_consumerSecret
	 */
	public function setConsumerSecret($_consumerSecret){
	 	$this->_consumerSecret = $_consumerSecret;
	}

	/**
	 * Define um token
	 * @param $_token
	 */
	public function setToken($_token){
	 	$this->_token = $_token;
	}

	/**
	 * Define o Token Secret
	 * @param $_tokenSecret
	 */
	public function setTokenSecret($_tokenSecret){
	 	$this->_tokenSecret = $_tokenSecret;
	}

	/**
	 * Executa uma requisição HTTP GET
	 * @param $path
	 * @param array $params
	 * @return string
	 * @throws MapiException
	 */
	public function get($path, $params = array()){
		$opt[CURLOPT_HTTPGET] = true;
	 	$request = $this->makeRequest($path, "GET", $opt, $params);
	 	return $request;
	}

	/**
	 * Executa requisição HTTP POST
	 * @param $path
	 * @param array $params
	 * @return string
	 * @throws MapiException
	 */
	public function post($path, $params = array()){		
		$opt[CURLOPT_POST] = true;
		$opt[CURLOPT_POSTFIELDS] = $params;
	 	
		$request = $this->makeRequest($path, "POST", $opt, $params);
	 	
		return $request;
	}

	/**
	 * Executa uma requisição HTTP PUT
	 * @param $path
	 * @param array $arrData
	 * @return string
	 * @throws MapiException
	 */
	public function put($path, $arrData = array()){
		$arrData = self::setUtf8($arrData);
		$txt = json_encode($arrData);
		
		$putString = "str=".urlencode($txt);
		$putFile = tmpfile(); 
		fwrite($putFile, $putString); 
		fseek($putFile, 0); 

		$opt[CURLOPT_PUT] = true;
		$opt[CURLOPT_INFILE] = $putFile;
		$opt[CURLOPT_INFILESIZE] = strlen($putString);
	 	
		$request = $this->makeRequest($path, "PUT", $opt);
	 	
		fclose($putFile);
		
		return $request;
	}

	/**
	 * Executa uma requisição HTTP DELETE
	 * @param $path
	 * @return string
	 * @throws MapiException
	 */
	public function delete($path){
		$opt[CURLOPT_CUSTOMREQUEST] = "DELETE";
	 	$request = $this->makeRequest($path, "DELETE", $opt);
	 	return $request;
	}

	/**
	 * Codifica uma string para utf-8
	 * @param $arrData
	 * @return mixed
	 */
	public static function setUtf8($arrData){
		foreach ($arrData as $key => $v) {
			if(is_array($v)){
				$arrData[$key] = self::setUtf8($v);
			} else {
				if($v){
					$arrData[$key] = utf8_encode($v);
				}
			}
		}
		return $arrData;
	}

	/**
	 * Cria uma request através de uma url
	 * @param $path
	 * @param string $method
	 * @param array $opts
	 * @param array $params
	 * @return string
	 * @throws MapiException
	 * @throws OAuthException
	 */
	private function makeRequest($path, $method = "GET", $opts = array(), $params = array()){	
		
		$url = $this->getUrlBase()."/".$path;
		
		/*
		 * oAuth
		 */
		$consumer = new OAuthConsumer($this->_consumerKey, $this->_consumerSecret);
		$token = new OAuthToken($this->_token, $this->_tokenSecret);
		
		$request = OAuthRequest::from_consumer_and_token($consumer, $token, $method, $url);
		
		foreach ($params as $name => $value) {
			$request->set_parameter($name, $value);
		}		
		
		$request->sign_request(new OAuthSignatureMethod_HMAC_SHA1(), $consumer, $token);

		$oAuthHeader = $request->to_header();
		
	 	$ch = curl_init();
	 	/*
	 	 * Cabeçalho padrão
	 	 */
	 	$headers[] = 'Accept: application/json'; // options json, xml e php
	 	$headers[] = 'Expect:';
	 	$headers[] = $oAuthHeader;
	 	/*
	 	 * Paramêtros
	 	 */
	 	$opts[CURLOPT_URL] 			= $url;	 	
	 	$opts[CURLOPT_HTTPHEADER] 	= $headers;
	 	$opts[CURLOPT_RETURNTRANSFER] 	= true;
	 	$opts[CURLOPT_SSL_VERIFYPEER] 	= false;
	 	
	 	curl_setopt_array($ch, $opts);
	 	/*
	 	 * Execução
	 	 */
	 	$result = curl_exec($ch);
	 	
	 	if ($result === false){
	 		$e = new MapiException(
	 				array('error_code' => curl_errno($ch),
						  'error'      => array(
								'message' => curl_error($ch),
								'type'    => 'CurlException'
								)
							)
						);
			curl_close($ch);
			throw $e;
	 	} else {
	 		$arrResult = json_decode($result, true);
	 		if(isset($arrResult['response']['erro']) && $arrResult['response']['erro'] == 1){
	 			$e = new MapiException(
	 				array('error_code' => $arrResult['response']['status'],
						  'error'      => array(
								'message' => utf8_decode($arrResult['response']['mensagem']),
								'type'    => 'MapiException',
	 							'code'	  => $arrResult['response']['cod_erro']
								)
							)
						);
				curl_close($ch);
				throw $e;
	 		}
	 	}
	 	curl_close($ch);

	 	if(!empty($arrResult['result'])){
	 		return $arrResult['result'];
	 	} else {
	 		return utf8_decode($arrResult['response']['mensagem']);
	 	}
	}
}