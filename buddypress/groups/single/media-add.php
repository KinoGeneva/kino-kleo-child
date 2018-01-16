<?php
/* Formulaire d'ajout de médias
 * les groupes de champs ACF sont affichés sur les portfolios selon les réglages des groupes de champs ACF dans le backend
 * l'association entre le portfolio et le groupe se fait via une donnée meta du groupe buddypress
 */
 

//meta info, données nécessaire à l'enregistrement
$group_id = bp_get_group_id(); 
$fiche_projet_post_id = groups_get_groupmeta($group_id, 'fiche-projet-post-id');

//id du post renseigné? On l'édite. 
if ( !empty( $fiche_projet_post_id ) ) {
	
	//affiche les médias attachés
	echo '<div style="clear: both; margin-top: 20px;">';
	echo '<h2>Photos du tournage</h2>';
	echo do_shortcode( '[gallery size="thumbnail" link="file" columns="8" id="'. $fiche_projet_post_id .'"]' );
	echo '</div>';
	
	wp_enqueue_media( ); //Insérer un média sans l'associer au portfolio car le membre n'a pas les droits de le faire
?>
<form id="img_projet" method="post">
	<input id="img_upload" type="button" value="Ajouter des photos de tournage" data-uploader_title="<?php _e('Media Library') ?>"/>
	<p>Seules les images au format JPG sont autorisées. Les autres types fichiers se seront pas ajoutés à la galerie.</p>
	<div id="new_img_container">
	</div>	
	
	<div style="text-align: center; clear: both;">
		<input type="submit" value="Enregistrer" style="font-size: 150%;"/>
	</div>
</form>

<?php
	//associer les nouvelles images au projet après l'ajout
	if(isset($_POST['img']) ){

		foreach($_POST['img'] as $id => $img){
			$post = array(
			'ID' => $id,
			'post_parent' => $fiche_projet_post_id,		
			);
			wp_update_post($post, true);
		}
	}

	
}
//la fiche projet doit exister
else {
	echo "<p>La fiche du projet n'existe pas encore. Veuillez d'abord créer la fiche projet (en l'enregistrant une première fois) pour pouvoir ajouter des médias.</p>";
}

?>
	


