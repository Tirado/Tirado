<?php
/**
* Class HTTPRequest
* Fourni des informations sur la requête client
**/
abstract class HTTPRequest {
	
	/**
	* Methode request
	* @return request/false
	*/
	static function request($name = null) {
		if($name === null) return $_REQUEST;
		$name = htmlspecialchars($name);

		if($name === 'uc')
			return (isset($_REQUEST[$name])) ? $_REQUEST[$name] : 'accueil';
		else
			return (isset($_REQUEST[$name])) ? $_REQUEST[$name] : false;
		
	}

}