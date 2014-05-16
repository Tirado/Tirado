<?php
session_start();
require_once("util/fonctions.inc.php");
require_once("util/class.pdoLafleur.inc.php");

// SClass ManagerUser et HTTPRequest //
require_once("util/ManagerUser.php");
require_once("util/HTTPRequest.php");

$pdo = PdoLafleur::getPdoLafleur();

// ManagerUser //
ManagerUser::init();
// Fin ManagerUser //

include("vues/v_entete.php") ;
include("vues/v_bandeau.php") ;

	// Request uc & action //
$uc = HTTPRequest::request('uc');
$action = HTTPRequest::request('action');
	// Fin Request uc & action //

switch($uc)
{
	case 'accueil':
	include("vues/v_accueil.php");
	break;
	case 'voirProduits' :
	include("controleurs/c_voirProduits.php");
	break;
	case 'gererPanier' :
	include("controleurs/c_gestionPanier.php");
	break; 
	case 'administrer' :
	include("controleurs/c_gestionProduits.php");
	break;  
	default:

	break;
}
include("vues/v_pied.php") ;

?>