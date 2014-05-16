<script type="text/javascript">
	var categorie = '<?php echo $categorie; ?>';
	function confirmDelete(id,name) {
		var r=confirm("Etes-vous sur de vouloir supprimer : "+name+" ?");
		if (r==true)
		{
			window.location = "index.php?uc=administrer&categorie="+categorie+"&produit="+id+"&action=delete";
		}
		else
		{
			
		}
	}
</script>
<div id="produits">

<?php
if($uc == 'administrer') {

	$categorie = HTTPRequest::request('categorie');
	echo'
<div id="add">
<a href="?uc=administrer&categorie='.$categorie.'&action=add">
<div class="addProduct"><img src="images/admin/add.png"> Ajouter un produit</div>
</a>
</div>';
	
}
	echo'<div id="produit">';
	foreach( $lesProduits as $unProduit) 
	{
		$id = $unProduit['id'];
		$description = $unProduit['description'];
		$prix=$unProduit['prix'];
		$image = $unProduit['image'];
		?>	
		<ul>
			<li><img src="<?php echo $image ?>" alt=image /></li>
			<li><?php echo $description ?></li>
			<li><?php echo " : ".$prix." Euros" ?>

				<?php
				if($uc == 'administrer'){
					echo'<li><a href="index.php?uc='.$uc.'&categorie='.$categorie.'&produit='.$id.'&action=edit"> 
					<img src="images/admin/edit.png" title="Editer le produit" style="cursor:pointer; border:0;"></a></li>
					<li>
						<img onclick="confirmDelete(this.id,this.name);" id="'.$id.'" name="'.$description.'" src="images/admin/delete.png" title="Supprimer le produit" style=" cursor:pointer; border:0;"></li>';
					}else{
						echo'<li><a href="index.php?uc='.$uc.'&categorie='.$categorie.'&produit='.$id.'&action=ajouterAuPanier"> 
						<img src="images/mettrepanier.png" title="Ajouter au panier" style="border:0;"></a></li>';
					}
					?>
					
					
				</ul>
				
				
				
				<?php			
			}
			?>
			</div>
		</div>
