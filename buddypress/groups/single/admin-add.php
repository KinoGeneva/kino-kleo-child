<?php

/* ajout ACF en frontend sur les projets buddypress
 * sources:
 * https://www.advancedcustomfields.com/resources/acf_form/
 * http://thestizmedia.com/front-end-posting-with-acf-pro/
 * 
 * Formulaire fiche projet:
 * les groupes de champs ACF sont affichés sur les portfolios selon les réglages des groupes de champs ACF dans le backend
 * les portfolio sont créés via le formulaire front-end (lien dans les onglets admin du groupe) lors de la première édition de la fiche-projet
 * le titre du projet n'est pas défini par un champ ACF mais sous "gestion du projet"
 * Il défini le titre du portfolio mais est mis à jour seulement quand on édite de la fiche projet
 * l'association entre le portfolio et le groupe se fait via une donnée meta du groupe buddypress
 */

//meta info, données nécessaire à l'enregistrement
$group_id = bp_get_group_id(); 
$fiche_projet_post_id = groups_get_groupmeta($group_id, 'fiche-projet-post-id');

//id du post existe en tant que donnée meta du groupe? On édite le post.  
if ( !empty( $fiche_projet_post_id ) ) {
	$projet_form = array(
		'post_id'	=> $fiche_projet_post_id,
		'field_groups' => array(3147,3164),
		'submit_value'	=> 'Mettre à jour la fiche projet',
		'updated_message'    => 'Le projet a été mis à jour',
		'html_before_fields' => '
		<input type="hidden" name="acf[group_id]" value="'. $group_id .'">
		<input type="hidden" name="acf[group-name]" value="'. bp_get_group_name() .'" />',
	); 
}
//sinon, créeation d'un post
else {
	$projet_form = array(
		'post_id'		=> 'new_post',
		'new_post'		=> array(
			'post_type'		=> 'portfolio',
			'post_status'		=> 'draft',
			'comment_status' => 'closed',
			'tax_input'    => array(
				'portfolio-category' => array(339)
				)
			),
		'field_groups' => array(3147,3164),
		'updated_message'    => 'Le projet a été créé',
		'submit_value'		=> 'Créer la fiche projet',
		'html_before_fields' => '
		<input type="hidden" name="acf[group_id]" value="'. $group_id .'">
		<input type="hidden" name="acf[group-name]" value="'. bp_get_group_name() .'" />',
	);
}
acf_form($projet_form);	
?>
	


