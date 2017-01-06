<?php
/* Formulaire fiche projet
 * les groupes de champs ACF sont affichés sur les articles de la catégorie fiches-projet
 * les articles sont créés dans le formulaire front-end affiché ici, dans les onglets admin du groupe
 * l'association entre l'article et le groupe se fait dans une donnée meta du groupe buddypress
 */
	
//meta info, données nécessaire à l'enregistrement
$group_id = bp_get_group_id(); 
$fiche_projet_post_id = groups_get_groupmeta($group_id, 'fiche-projet-post-id');

//affiche le réalisateur
global $groups_template;

//id du post renseigné? On l'édite. Les champs ACF sont affichés car l'article est dans la catégorie fiches-projet 
if ( !empty( $fiche_projet_post_id ) ) {
	$projet_form = array(
		'post_id'	=> $fiche_projet_post_id,
		'submit_value'	=> 'Mettre à jour la fiche projet',
		'updated_message'    => 'Le projet a été mis à jour',
		'html_before_fields' => '
		<input type="hidden" name="acf[group_id]" value="'. $group_id .'">
		<input type="hidden" name="acf[group-name]" value="'. bp_get_group_name() .'" />',
	); 
}
//sinon, nouvelle fiche (=>première fois qu'on édite les détails du groupe)
else {
	$projet_form = array(
		'post_id'		=> 'new_post',
		'new_post'		=> array(
			'post_type'		=> 'post',
			'post_status'		=> 'publish',
			'comment_status' => 'closed',
			'post_category' => array(308)
			),
		'field_groups' => array(3147,3164),
		'updated_message'    => 'Le projet a été mis à jour',
		'submit_value'		=> 'Mettre à jour la fiche projet',
		'html_before_fields' => '
		<input type="hidden" name="acf[group_id]" value="'. $group_id .'">
		<input type="hidden" name="acf[group-name]" value="'. bp_get_group_name() .'" />',
	);
}
acf_form($projet_form);	
?>
	


