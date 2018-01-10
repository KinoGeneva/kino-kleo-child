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
	$media_form = array(
		'post_id'	=> $fiche_projet_post_id,
		'submit_value'	=> 'Enregistrer les médias',
		'updated_message'    => 'Les médias ont bien été enregistrés',
		'field_groups' => array(3224)
	); 
	acf_form($media_form);	
}
//la fiche projet doit exister
else {
	echo "<p>La fiche du projet n'existe pas encore. Veuillez d'abord créer la fiche projet (en l'enregistrant une première fois) pour pouvoir ajouter des médias.</p>";
}

?>
	


