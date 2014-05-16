<style>
#form_edit input, label {
	margin-top: 6px;
}
</style>
<?php

if(!empty($product)) { ?>
<form id="form_edit" method="post" enctype="multipart/form-data">

<input type="hidden" name="data[id]" value="<?php echo $id; ?>">
<fieldset style="margin:0 auto;">
<img src="<?php echo $product[0]['image']; ?>" style="float:right;">
<label for="data[description]">Description:</label>
<textarea id="data[description]" name="data[description]"><?php echo $product[0]['description']; ?></textarea><br>
<label for="data[prix]">Prix:</label>
<input type="text" name="data[prix]" id="data[prix]" value="<?php echo $product[0]['prix']; ?>"><br>
<label for="data[idCategorie]">Categorie:</label>
<select name="data[idCategorie]" id="data[categorie]">
			<?php
			foreach ($lesCategories as $key => $value) {
				$idCategorie = $value['id'];
				$libCategorie = $value['libelle'];
				echo'<option ';
				echo ($idCategorie == $product[0]['idCategorie']) ? 'selected' : ' ';
				echo' value="'.$idCategorie.'">'.$libCategorie.'</option>'."\n";
				}
				?>

			</select><br>

     <input type="file" name="data[img]" id="data[img]" />
<input type="submit" value="Mise à jour" name="data[form_edit]">
</fieldset>
</form>
<?php }else{ echo "L'id du produit n'existe pas.";} ?>