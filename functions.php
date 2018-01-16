<?php
/**
 * @package WordPress
 * @subpackage Kleo
 * @author SeventhQueen <themesupport@seventhqueen.com>
 * @since Kleo 1.0
 */


/* Initialize (CSS, Scripts, Widgets)
******************************/

require_once('functions/init.php');


/* Add body class, depending of site */

//* Add custom body class to the head

add_filter( 'body_class', 'kino_body_class' );

function kino_body_class( $classes ) {
	
	$host = $_SERVER['HTTP_HOST'];
	if ( $host == 'kinogeneva.ch' ) {
		$classes[] = 'live-site';
	} else {
		$classes[] = 'test-site';
	}
		
	return $classes;
}


/* admin interface
******************************/

require_once('functions/admin.php');

/* Code fore page header
******************************/

require_once('functions/header-output.php');

/* BuddyPress Functionality
******************************/

require_once('functions/bp-user-fields.php');

require_once('functions/bp-group-tabs.php');

require_once('functions/bp-messages.php');

require_once('functions/bp-field-validation.php');

//d'abord tester que le plugin ACF est actif
/**
 * Detect plugin. For use on Front End only.
 */
include_once( ABSPATH . 'wp-admin/includes/plugin.php' );

// check for plugin using plugin name
if ( is_plugin_active( 'advanced-custom-fields-pro/acf.php' ) ) {
  //plugin is activated
  require_once('functions/bp-group-add.php');
} 

/* Portfolio aka Film  Pages
******************************/

require_once('functions/portfolio-films.php');

/* Admin Pages
******************************/

require_once('functions/js-tablesort.php');

/* Forcer éditeur texte par défaut
***************************************/

// Set the editor to HTML ("Text")
add_filter( 'wp_default_editor', create_function(null,'return "html";') );


/* Custom Login Screen
***************************************/

function kino_login_screen() {
	echo '<link rel="stylesheet" type="text/css" href="' . get_stylesheet_directory_uri() . '/login/login.css" />';
}
add_action( 'login_head', 'kino_login_screen' );



/* Login redirection
 * Voir discussion: https://bitbucket.org/ms-studio/kinogeneva/issues/26/
 * UPDATE: il est finalement plus efficace de rediriger vers /inscription-kino/ via la fonctionalité incluse dans les options KLEO, sous Miscellaneous
***************************************/

/* Login redirection for modal window
 * Voir discussion: https://bitbucket.org/ms-studio/kinogeneva/issues/112/
***************************************/


// Filter for kleo_title_section
// $args = apply_filters('kleo_title_args', $args);

add_filter('kleo_title_args', 'kino_title_filter',10,1);

function kino_title_filter( $args ) {
	
	if ( function_exists( 'bp_is_user' ) ) {
	
		if ( bp_is_user() ) {
		
			$title_content = $args['title'];
			
			$member_avatar = '<div class="item-avatar rounded kino-title-avatar">'. bp_core_fetch_avatar( array("class"  => "avatar kleo-rounded", "alt" => "") ) .'</div>';
			
			$args['title'] = $member_avatar . $title_content ;
			
			// ajouter le @username... ou pas.
			
			$title_username = ' <span class="user-nicename">@'. bp_get_displayed_user_mentionname() .'</span>';
			
			// $args['title'] .= $title_username;
			
			
			// ajouter les boutons:
			// do_action( 'bp_member_header_actions' ); 
			
			// bp_send_public_message_button
			
			// bp_send_private_message_button = OK
			
			// $title_buttons = do_action( 'bp_member_header_actions' );
			
			$title_buttons = '';
			
			// Test if function exists: bp_get_send_message_button()
			
			 if ( function_exists( 'bp_get_send_message_button' ) ) {
			 	
			 	$title_buttons .= bp_get_send_message_button();
			 
			 }
			 
			 if ( function_exists( 'bp_get_send_public_message_button' ) ) {
			 	
			 	$title_buttons .= bp_get_send_public_message_button();
			 
			 }
			 
			 if ( function_exists( 'bp_follow_get_add_follow_button' ) ) {
			 
			 	$title_buttons .= bp_follow_get_add_follow_button();
			 }
	
			$args['title'] .= $title_buttons;
			
			// class="user-nicename">@<?php bp_displayed_user_mentionname(); 
			
		}
			
	} // if function_exists
	
	return $args;
	
} // end kino_title_filter

#https://bitbucket.org/ms-studio/kinogeneva/issues/118/a-la-fin-de-l-dition-du-profil-redirection
#https://buddypress.org/support/topic/how-to-redirect-users-to-their-profile-after-they-edit-their-profile/
add_action( 'xprofile_updated_profile', 'SaveEditsRedirect', 12 );
function SaveEditsRedirect() {
	global $bp;
	if ( is_user_logged_in() && bp_get_current_profile_group_id()==22 ) {
		wp_redirect( $bp->displayed_user->domain );
		exit;
	}
}

//redir sur l'onglet profil à l'édition
//https://bitbucket.org/ms-studio/kinogeneva/issues/265/
add_action( 'xprofile_screen_edit_profile', 'conditionsEditsRedirect', 12 );
function conditionsEditsRedirect() {
	global $bp;
	if ( is_user_logged_in() && bp_get_current_profile_group_id()==1 ) {
		wp_redirect( $bp->displayed_user->domain .'/profile/edit/group/19/');
		exit;
	}
}

#https://premium.wpmudev.org/blog/hide-wordpress-media-uploader-button/
/**
 * Allow access to own content only
 */
function my_authored_content($query) {

	//get current user info to see if they are allowed to access ANY posts and pages
	$current_user = wp_get_current_user();
	// set current user to $is_user
	$is_user = $current_user->user_login;

	//if is admin or 'is_user' does not equal #username
	if (!current_user_can('edit_pages')){
		//if in the admin panel
		if($query->is_admin) {

			global $user_ID;
			$query->set('author',  $user_ID);

		}
		return $query;
	}
	return $query;
}
add_filter('pre_get_posts', 'my_authored_content');
