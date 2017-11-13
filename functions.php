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

require_once('functions/bp-group-add.php');


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
	
	if ( bp_is_user() ) {
	
		$title_content = $args['title'];
		
		$member_avatar = '<div class="item-avatar rounded kino-title-avatar">'. bp_get_member_avatar( array("class"  => "avatar kleo-rounded", "alt" => "") ) .'</div>';
		
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
		 	
		 	$title_buttons = bp_get_send_message_button();
		 
		 }
		 
		 if ( function_exists( 'bp_get_send_public_message_button' ) ) {
		 	
		 	$title_buttons = bp_get_send_public_message_button();
		 
		 }
		

		$args['title'] .= $title_buttons;
		
		// class="user-nicename">@<?php bp_displayed_user_mentionname(); 
		
		
	}
	
	return $args;
	
}

#https://bitbucket.org/ms-studio/kinogeneva/issues/118/a-la-fin-de-l-dition-du-profil-redirection
#https://buddypress.org/support/topic/how-to-redirect-users-to-their-profile-after-they-edit-their-profile/
add_action( 'xprofile_updated_profile', 'SaveEditsRedirect', 12 );
function SaveEditsRedirect() {
	global $bp;
	if ( is_user_logged_in() && bp_get_current_profile_group_id()==21 ) {
		wp_redirect( $bp->loggedin_user->domain );
		exit;
	}
}
