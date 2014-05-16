<style>
	#form_add input, label {
		margin-top: 6px;
	}
</style>

<form id="form_add" method="post" enctype="multipart/form-data">

	<fieldset style="margin:0 auto;">
		<p style="text-align:center;"><u>Nouveau Produit</u></p>
		<label for="data[description]">Description:</label>
		<textarea id="data[description]" name="data[description]"></textarea><br>
		<label for="data[prix]">Prix:</label>
		<input type="text" name="data[prix]" id="data[prix]" value=""><br>
		<label for="data[idCategorie]">Categorie:</label>
		<select name="data[idCategorie]" id="data[idCategorie]">
			<?php
			foreach ($lesCategories as $key => $value) {
				$idCategorie = $value['id'];
				$libCategorie = $value['libelle'];
				echo'<option ';
				echo ($idCategorie == $categorie) ? 'selected' : ' ';
				echo' value="'.$idCategorie.'">'.$libCategorie.'</option>'."\n";
				}?>

			</select>
				<br>	
     
     <label for="data[img]">Image</label>
     <input type="file" name="data[img]" id="data[img]" /><br />


<br>
			<input type="submit" value="Enregistrer" name="data[form_add]">
		</fieldset>

	</form>