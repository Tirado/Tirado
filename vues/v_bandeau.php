<header>




<div id="box_left"><img src="images/lafleur.gif"	alt="Lafleur" title="Lafleur" id="logo" /></div>
<div id="box_right">
	<!--  Menu haut-->
<ul id="menu">
	<li><a href="index.php?uc=accueil"> Accueil </a></li>
	<li><a href="index.php?uc=voirProduits&action=voirCategories"> Voir le catalogue de fleurs </a></li>
	<li><a href="index.php?uc=gererPanier&action=voirPanier"> Voir son panier </a></li>
	<li><a href="index.php?uc=administrer"> Administrer </a></li>
</ul>
</div>

<!-- Images En-tête -->
<!--<img src="images/menuGauche.gif"	alt="Choisir" title="Choisir"/>-->




</header>
<div id="container_content">

<?php
if(ManagerUser::isConnected())
	echo '<div id="box_login"><span style="display:block; text-align:center;">'.ManagerUser::user('name').'</span><a href="?uc=administrer&action=logout">Deconnexion</a></div>';
?>