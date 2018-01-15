<?php

/* hooks lors de l'enregistrement d'un portfolio avec la fiche-projet et ACF
 * https://www.advancedcustomfields.com/resources/acf-save_post/
 */

add_action('acf/save_post', 'custom_acf_save_post', 20);
function custom_acf_save_post( $post_id ) { //$post_id (int|string) the ID of the post (or user, term, etc) being saved
	
	//si pas de data ACF ou pas d'id de groupe on sort (=> ne concerne que le formulaire "fiche-projet")
	if( empty($_POST['acf']) || empty($_POST['acf']['group_id'] ) ) {
		return;
	}
		
	//mises à jour du post avec les informations du groupe
		
	//1. id du post
	
	$data['ID'] = $post_id;
	
	//2. définit le titre du groupe/projet comme titre du nouveau portfolio

	$project_title = wp_strip_all_tags($_POST['acf']['group-name']);
	
	$data['post_title'] = $project_title;
	$data['post_name'] = sanitize_title( $project_title );
	
	wp_update_post( $data );
	
	//3. meta de groupe -> création d'une ligne avec id de l'article

	$create_meta = groups_update_groupmeta( $_POST['acf']['group_id'],  'fiche-projet-post-id',  $post_id );

	//4. ajoute les nouveaux membres au groupe:
	//équipe
	foreach($_POST['acf']['field_586ed49ca1e4b'] as $i => $fields){
		//membre kino: field_586ed4aca1e4c
		$new_member = $fields['field_586ed4aca1e4c'];
		groups_accept_invite( $new_member, $_POST['acf']['group_id'] );
	}
	//comédiens
	foreach( $_POST['acf']['field_586e935c45950'] as $i => $new_member){
		groups_accept_invite( $new_member, $_POST['acf']['group_id'] );
	}

}

//nettoie le html avant de l'insérer
//suppression de la fonction  my_kses_post() devenue inutile
//https://www.advancedcustomfields.com/resources/acf_form/
//sanitize all $_POST data with the wp_kses_post() function. Defaults to true. Added in v5.6.5


//ajoute au header tous les scripts de ACF permettant d'enregistrer les données
add_action( 'get_header', 'tsm_do_acf_form_head', 1 );
function tsm_do_acf_form_head() {
	// Bail if not logged in or not able to post
	if ( ! ( is_user_logged_in() || current_user_can('edit_posts') ) ) {
		return;
	}
	acf_form_head();
}

#acf google map API
function my_acf_init() {
	acf_update_setting('google_api_key', 'AIzaSyDJ-H97yAa8Ze_h_Vu9Vd05of-i__ozsA4');
}
add_action('acf/init', 'my_acf_init');


function my_acf_google_map_api( $api ){
	$api['key'] = 'AIzaSyDJ-H97yAa8Ze_h_Vu9Vd05of-i__ozsA4';
	return $api;
}

add_filter('acf/fields/google_map/api', 'my_acf_google_map_api');

#menu des groups

//modification du sous-menu gestion du projet
function remove_group_admin_tab() {
	if ( ! bp_is_group() || ! ( bp_is_current_action( 'admin' ) && bp_action_variable( 0 ) ) ) {
		return;
	}
	// Add the admin subnav slug you want to hide in the following array
	$hide_admin_tabs = array(
		'group-settings' => 1,
		'group-cover-image' => 1,
		'delete-group' => 1,
		'forum' => 1,
		'notifications' => 1,
		'docs' => 1,
		'group-avatar' => 1
	);
	$parent_nav_slug = bp_get_current_group_slug() . '_manage';
	// Remove the nav items
	foreach ( array_keys( $hide_admin_tabs ) as $tab ) {
	  // Since 2.6, You just need to add the 'groups' parameter at the end of the bp_core_remove_subnav_item
		bp_core_remove_subnav_item( $parent_nav_slug, $tab, 'groups' );
	}
	
}
add_action( 'bp_actions', 'remove_group_admin_tab', 100 );

//idem pour le menu du groupe
function remove_group_home_tab() {
	if ( ! bp_is_group()) {
		return;
	}
	$hide_home_tabs = array(
		//'announcements' => 1,
		'notifications' => 1,
		'members' => 1,
	);
	$parent_nav_slug = bp_get_current_group_slug() ;

	foreach ( array_keys( $hide_home_tabs ) as $tab ) {
	bp_core_remove_subnav_item( $parent_nav_slug, $tab, 'groups' );
		bp_core_remove_subnav_item( $parent_nav_slug, $tab, 'groups' );
	}
	
}
add_action( 'bp_actions', 'remove_group_home_tab', 100 );

//ajout d'un item "fiche-projet" au menu du groupe pour admin seulement
function add_group_tab() {
	if ( ! bp_is_group() || ( bp_is_current_action( 'create' )) || (!bp_group_is_admin()  && !is_super_admin() ) ){
		return;
	}
	$parent_nav_slug = bp_get_current_group_slug() ;
	$parent_nav_url =  bp_get_group_permalink( groups_get_current_group() ) ;

	$add_group_tab_args = array( 
		'name' => 'Éditer la fiche projet', 
		'slug' => 'fiche-projet',
		'default_subnav_slug' => 'fiche-projet',
		'parent_slug' => $parent_nav_slug,
		'parent_url' => $parent_nav_url,
		'screen_function' => 'fiche_projet_screen',
		'position' => 100,
	);
	$result = bp_core_new_subnav_item($add_group_tab_args, 'groups') ; 
}

//fonction à appeler:
function fiche_projet_screen() {
    add_action( 'bp_template_content', 'fiche_projet_form' );
	bp_core_load_template( apply_filters( 'bp_core_template_plugin', 'groups/single/admin' ) );
}

function fiche_projet_form() { 
	bp_get_template_part( 'groups/single/admin-add');
}
add_action( 'bp_actions', 'add_group_tab', 30 );

//ajout d'un item "médias" au menu du groupe pour tous les membres du projet
function add_group_media_tab() {
	if ( ! bp_is_group() || bp_is_current_action( 'create' ) || (!bp_group_is_admin()  && !is_super_admin() && !bp_group_is_member( groups_get_current_group() )) ){
		return;
	}
	$parent_nav_slug = bp_get_current_group_slug() ;
	$parent_nav_url =  bp_get_group_permalink( groups_get_current_group() ) ;

	$add_group_tab_args = array( 
		'name' => 'Médias', 
		'slug' => 'media-projet',
		'default_subnav_slug' => 'media-projet',
		'parent_slug' => $parent_nav_slug,
		'parent_url' => $parent_nav_url,
		'screen_function' => 'media_projet_screen',
		'position' => 101,
	);
	$result = bp_core_new_subnav_item($add_group_tab_args, 'groups') ; 
}

//fonction à appeler:
function media_projet_screen() {
    add_action( 'bp_template_content', 'media_projet_form' );
	bp_core_load_template( apply_filters( 'bp_core_template_plugin', 'groups/single/home' ) );
}

function media_projet_form() { 
	bp_get_template_part( 'groups/single/media-add');
}
add_action( 'bp_actions', 'add_group_media_tab', 30 );


?>
