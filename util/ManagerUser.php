<?php
/**
* Class ManagerUser
* @author Kévin Tirado
* @version 1.0
* @date 14/05/2014
*/

class ManagerUser {

	public static $_message = array();
	private static $_pdo;

	static public function init() {
		
		self::setPdo(PdoLafleur::getPdo());

	}

	/**
	* Permet de savoir si une session existe.
	* @return true/false
	**/
	static public function isConnected() {
		return isset($_SESSION['session']);
	}

	/**
	* Permet de detruire la session
	* @return true/false
	**/
	static public function logout() {
		unset($_SESSION['session']);
		header("Location: /");
	}

	static public function connect() {

		if(isset(HTTPRequest::request('data')['form_login'])) {
			$login = HTTPRequest::request('data')['login'];
			$password = HTTPRequest::request('data')['password'];
			$password = md5($password);

			$sql_Requete = "SELECT * from user  WHERE login = :login AND password = :password LIMIT 0,1";

			$sth =  self::getPdo()->prepare($sql_Requete, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
			$sth->execute(array(':login' => $login, ':password' => $password));
			$sth = $sth->fetchAll();

			if($sth == true){
				
				foreach ($sth[0] as $key => $value) {
					if(is_int($key)) unset($sth[0][$key]);
				}

				foreach ($sth[0] as $key => $value) {

					if(!isset($_SESSION['session'])) unset($_SESSION['session']);
					$_SESSION['session'][$key] = $value;
					header("Location: ?uc=administrer");
				}
				
			}
			else
				self::addMessage("Mot de passe ou login incorrect");

			return ($sth == true) ? true : false;
		}

	}

	static private function setPdo($instance) {
		self::$_pdo = $instance;
	}

	static private function getPdo() {
		return self::$_pdo;
	}

	static public function addMessage($msg) {
		self::$_message[] = $msg;
	}
	static public function getMessage() {
		if(!isset(self::$_message)) return false;
		$s_msg = self::$_message;
		//unset(self::$_message);
		return (isset($s_msg)) ? $s_msg : false;
	}

	static public function user($get) {
		
		return (isset($_SESSION['session'][$get])) ? $_SESSION['session'][$get] : false;
	}

}