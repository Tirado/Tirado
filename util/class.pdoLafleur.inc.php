<?php
/** 
* Classe d'accès aux données. 

* Utilise les services de la classe PDO
* pour l'application lafleur
* Les attributs sont tous statiques,
* les 4 premiers pour la connexion
* $monPdo de type PDO 
* $monPdoLafleur qui contiendra l'unique instance de la classe
*
* @package default
* @author Patrice Grand
* @version    1.0
* @link       http://www.php.net/manual/fr/book.pdo.php
*/

class PdoLafleur
{   		
    private static $serveur='mysql:host=localhost';
    private static $bdd='dbname=lafleur';   		
    private static $user='root' ;    		
    private static $mdp='' ;	

    private static $monPdo;
    private static $monPdoLafleur = null;
/**
* Constructeur privé, crée l'instance de PDO qui sera sollicitée
* pour toutes les méthodes de la classe
*/				
private function __construct()
{
    PdoLafleur::$monPdo = new PDO(PdoLafleur::$serveur.';'.PdoLafleur::$bdd, PdoLafleur::$user, PdoLafleur::$mdp); 
    PdoLafleur::$monPdo->query("SET CHARACTER SET utf8");
}
public function _destruct(){
    PdoLafleur::$monPdo = null;
}

public static function getPdo() {
    return self::$monPdo;
}
/**
* Fonction statique qui crée l'unique instance de la classe
*
* Appel : $instancePdolafleur = PdoLafleur::getPdoLafleur();
* @return l'unique objet de la classe PdoLafleur
*/
public  static function getPdoLafleur()
{
    if(PdoLafleur::$monPdoLafleur == null)
    {
        PdoLafleur::$monPdoLafleur= new PdoLafleur();
    }
    return PdoLafleur::$monPdoLafleur;  
}
/**
* Retourne toutes les catégories sous forme d'un tableau associatif
*
* @return le tableau associatif des catégories 
*/
public function getLesCategories()
{
    $req = "select * from categorie";
    $res = PdoLafleur::$monPdo->query($req);
    $lesLignes = $res->fetchAll();
    return $lesLignes;
}
public function deleteProduit($id)
{
    //if(empty($id)) return false;


   
$stmt =  self::getPdo()->prepare('SELECT idProduit FROM contenir WHERE idProduit = :id', array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
$stmt->bindValue(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    $r = $stmt->fetchAll();
    
    if(!empty($r)) {
        ManagerUser::addMessage('Le produit est présent dans une commande, il ne peut-être effacé maintenant.');
        return false;
    }
    
    $stmt =  self::getPdo()->prepare('DELETE FROM produit WHERE id = :id', array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
    $stmt->bindValue(':id', $id, PDO::PARAM_INT);
    $result = $stmt->execute();
    if($result)
        ManagerUser::addMessage('Produit supprimé .');
    else
        ManagerUser::addMessage('Un probleme est survenu, merci de recommencer');
    

    //return ($sth == true) ? true : ManagerUser::addMessage('Produit non trouvé');
}
/**
* Retourne sous forme d'un tableau associatif tous les produits de la
* catégorie passée en argument
* 
* @param $idCategorie 
* @return un tableau associatif  
*/

public function getLesProduitsDeCategorie($idCategorie)
{
    $req="select * from produit where idCategorie = '$idCategorie'";
    $res = PdoLafleur::$monPdo->query($req);
    $lesLignes = $res->fetchAll();
    return $lesLignes; 
}
/**
* Retourne les produits concernés par le tableau des idProduits passée en argument
*
* @param $desIdProduit tableau d'idProduits
* @return un tableau associatif 
*/
public function getLesProduitsDuTableau($desIdProduit)
{
    $nbProduits = count($desIdProduit);
    $lesProduits=array();
    if($nbProduits != 0)
    {
        foreach($desIdProduit as $unIdProduit)
        {
            $req = "select * from produit where id = '$unIdProduit'";
            $res = PdoLafleur::$monPdo->query($req);
            $unProduit = $res->fetch();
            $lesProduits[] = $unProduit;
        }
    }
    return $lesProduits;
    
}
/**
* Crée une commande 
*
* Crée une commande à partir des arguments validés passés en paramètre, l'identifiant est
* construit à partir du maximum existant ; crée les lignes de commandes dans la table contenir à partir du
* tableau d'idProduit passé en paramètre
* @param $nom 
* @param $rue
* @param $cp
* @param $ville
* @param $mail
* @param $lesIdProduit

*/
public function creerCommande($nom,$rue,$cp,$ville,$mail, $lesIdProduit )
{
    $req = "select max(id) as maxi from commande";
    echo $req."<br>";
    $res = PdoLafleur::$monPdo->query($req);
    $laLigne = $res->fetch();
    $maxi = $laLigne['maxi'] ;
    $maxi++;
    $idCommande = $maxi;
    echo $idCommande."<br>";
    echo $maxi."<br>";
    $date = date('Y/m/d');
    $req = "insert into commande values ('$idCommande','$date','$nom','$rue','$cp','$ville','$mail')";
    echo $req."<br>";
    $res = PdoLafleur::$monPdo->exec($req);
    foreach($lesIdProduit as $unIdProduit)
    {
        $req = "insert into contenir values ('$idCommande','$unIdProduit')";
        echo $req."<br>";
        $res = PdoLafleur::$monPdo->exec($req);
    }
}
public function addProduct($data,$image)
{
    
    

    if(empty($data['description'])) { 
        ManagerUser::addMessage('Il faut indiquer une description');
        return false;
    }

    if(empty($data['prix'])) {
        ManagerUser::addMessage('Il faut indiquer un prix');
        return false;
    }

    if(empty($data['idCategorie'])) {
        ManagerUser::addMessage('Il faut indiquer une categorie');
        return false;

    }
    $idCategorie = $data['idCategorie'];
    $lettreId = $idCategorie[0].'%';
    //
    $req = "select max(id) as maxi from produit WHERE id LIKE '$lettreId'";
    $res = PdoLafleur::$monPdo->query($req);
    $laLigne = $res->fetch();
    //
    $id = $idCategorie[0];
    $id .= (intval(substr($laLigne['maxi'],1)) <= 9) ? '0' : null;
    $id .= (intval(substr($laLigne['maxi'],1))+1);
    
    $req = "insert into produit values ('$id','".$data['description']."','".$data['prix']."','".$image."','".$data['idCategorie']."')";

    $res = PdoLafleur::$monPdo->exec($req);
    if($res)
        ManagerUser::addMessage("Tout est bon");
    else
        ManagerUser::addMessage("Erreur SQL");
}
public function getProduct($idProduct)
{

    $req = "SELECT * from produit where id = '$idProduct'";
    $res = PdoLafleur::$monPdo->query($req);
    $product = $res->fetchAll();
    return $product;

}

public function editProduct($data, $url = null)
{


    if(empty($data['description'])) { 
        ManagerUser::addMessage('Il faut indiquer une description');
        return false;
    }

    if(empty($data['prix'])) {
        ManagerUser::addMessage('Il faut indiquer un prix');
        return false;
    }

    if(empty($data['idCategorie'])) {
        ManagerUser::addMessage('Il faut indiquer une categorie');
        return false;

    }
    
    if(!empty($url)) {
    $sql_Requete = "UPDATE produit set image = :image WHERE id = :id";
    $sth =  self::getPdo()->prepare($sql_Requete, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
    $sth->execute(array(':image' => $url,':id' => $data['id']));
    }
    $sql_Requete = "UPDATE produit set idCategorie = :idCategorie, description = :description, prix = :prix WHERE id = :id";
    $sth =  self::getPdo()->prepare($sql_Requete, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
    $sth->execute(array('idCategorie' => $data['idCategorie'],':description' => $data['description'], ':prix' => $data['prix'],':id' => $data['id']));
    
}

}
?>