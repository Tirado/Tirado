<?php
if(!ManagerUser::isConnected())
	$action = 'login';
else $action = ($action != false) ? $action : 'gestionProduits';

// Methode static connect //
ManagerUser::connect();
// Fin Methode static connect //
$id = HTTPRequest::request('produit');
$categorie = HTTPRequest::request('categorie');
$lesCategories = $pdo->getLesCategories();

if(HTTPRequest::request('action') == 'logout'){
	$action = "login";
	ManagerUser::logout();
}

include("vues/v_categories.php");
function upload($file) {

	if($file['data']['error']['img'] > 0) ManagerUser::addMessage("Erreur lors du transfert");

	$type = strtolower(  substr(  strrchr($file['data']['name']['img'], '.')  ,1)  );
	$typeActif = array('jpg','png','jpeg');

	if(!in_array($type, $typeActif)){
		ManagerUser::addMessage('Format image invalide');
		return false;
	}
	if($file['data']['size']['img'] > 3145728) {
		ManagerUser::addMessage('Le fichier est trop grand');
		return false;
	}

	$folder = 'images/';
	switch (HTTPRequest::request('data')['idCategorie']) {
		case 'com':
		$folder .= 'compo/';
		break;
		case 'fle':
		$folder .= 'fleurs/';
		break;
		case 'pla':
		$folder .= 'plantes/';
		break;

		default:
		ManagerUser::addMessage('Erreur, nom de catégorie incorrect');
		return false;
		break;
	}
	$folder .= $file['data']['name']['img'];

	$resultat = move_uploaded_file($file['data']['tmp_name']['img'],$folder);
	if (!$resultat){
		ManagerUser::addMessage("Un problème est survenu pendant l'upload.");
		return false;
	}else{
		return $folder;
	}

}



switch($action)
{
	case 'logout':
	

	include("vues/utili/v_message.php");
	break;
	case 'login':
	include("vues/login/v_login.php");
	break;
	case 'voirProduits' :
	$categorie = (isset($categorie)) ? $categorie : 'com';
	$lesProduits = $pdo->getLesProduitsDeCategorie($categorie);
	include("vues/v_produits.php");
	break;

	case'delete';
	$pdo->deleteProduit($id);
	include("vues/utili/v_message.php");
	break;
	case 'edit':
	
	if(HTTPRequest::request('data')['form_edit']) {
		
var_dump($_FILES['data']['size']['img']);
		if($_FILES['data']['size']['img'] > 0) {
			$statut = upload($_FILES);

			if($statut == true)
			$pdo->editProduct(HTTPRequest::request('data'), $statut);
		}else{
			$pdo->editProduct(HTTPRequest::request('data'));
		}

	}

	$product = $pdo->getProduct($id);
	include("vues/utili/v_message.php");
	include('vues/administration/v_edit.php');
	break;

	case 'add':
	
	if(isset(HTTPRequest::request('data')['form_add'])) {

		if(isset($_FILES) && $_FILES['data']['size']['img'] != 0) {
			$statut = upload($_FILES);
		}else{
			$statut = false;
			ManagerUser::addMessage('Il faut choisir une image pour le nouveau produit');
		}

		if($statut == true) {
			$pdo->addProduct(HTTPRequest::request('data'),$statut);
		}

	}
	include("vues/utili/v_message.php");
	include('vues/administration/v_add.php');
	break;
	default:
	
	break;
}

?>