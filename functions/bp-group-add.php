<?php
#ajout ACF en frontend sur les projets buddypress
#https://www.advancedcustomfields.com/resources/acf_form/
#http://thestizmedia.com/front-end-posting-with-acf-pro/

//lors de l'enregistrement d'un article avec ACF, le lier au projet

add_action('acf/save_post', 'custom_acf_save_post', 20);
function custom_acf_save_post( $post_id ) {
	
	//1. si pas de date ACF ou pas d'id groupe on sort
	if( empty($_POST['acf']) || empty($_POST['acf']['group_id'] ) ) {
		return;
	}

	//1. id du nouvel article
	
	$data['ID'] = $post_id;
	
	//2. définit le titre du groupe/projet comme titre du nouvel article

	$project_title = wp_strip_all_tags($_POST['acf']['group-name']);
	
	$data['post_title'] = $project_title;
	$data['post_name'] = sanitize_title( $project_title );
	
	wp_update_post( $data );
	
	//3. meta de groupe -> création / mise à jour d'une ligne avec id de l'article
	
	$create_meta = groups_update_groupmeta( $_POST['acf']['group_id'],  'fiche-projet-post-id',  $post_id );
	
	//4. ajoute les nouveaux membres au groupe
	foreach($_POST['acf']['field_586ed49ca1e4b'] as $i => $fields){
		//membre kino: field_586ed4aca1e4c
		$new_member = $fields['field_586ed4aca1e4c'];
		groups_accept_invite( $new_member, $_POST['acf']['group_id'] );
	}
}

//nettoie le html avant de l'insérer

function my_kses_post( $value ) {
	// is array
	if( is_array($value) ) {
		return array_map('my_kses_post', $value);
	}
	// return
	return wp_kses_post( $value );
}
add_filter('acf/update_value', 'my_kses_post', 10, 1);


//ajoute au header tous les scripts de ACF permettant d'enregistrer les données

add_action( 'get_header', 'tsm_do_acf_form_head', 1 );
function tsm_do_acf_form_head() {
	// Bail if not logged in or not able to post
	if ( ! ( is_user_logged_in() || current_user_can('publish_posts') ) ) {
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
		'announcements' => 1,
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
	if ( ! bp_is_group() || (!bp_group_is_admin()  && !is_super_admin() ) ){
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

?>
