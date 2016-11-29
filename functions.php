<?php
/**
 * @package WordPress
 * @subpackage Kleo
 * @author SeventhQueen <themesupport@seventhqueen.com>
 * @since Kleo 1.0
 */


/* Add body class, depending of site */

//* Add custom body class to the head

add_filter( 'body_class', 'kino_body_class' );

function kino_body_class( $classes ) {
	
	$host = $_SERVER['HTTP_HOST'];
	if ( $host == 'kinogeneva.4o4.ch' ) {
		$classes[] = 'test-site';
	} else {
		$classes[] = 'live-site';
	}
		
	return $classes;
}

/* Load Styles */

function kino_register_styles() {

	/**
	 * Custom CSS
	 */

	wp_enqueue_style( 
			'main-style', 
			get_stylesheet_directory_uri() . '/css/dev/00-main.css', // main.css
			false, // dependencies
			'2016.11.28' // version
	); 
	
	/*
	 * Conditional CSS for the DEV site
	 *
	*/
	
	$host = $_SERVER['HTTP_HOST'];
	if ( $host == 'kinogeneva.4o4.ch' ) {
	    wp_enqueue_style( 
	    		'test-style', 
	    		get_stylesheet_directory_uri() . '/css/test/00-testing.css', // main.css
	    		false, // dependencies
	    		null // version
	    ); 
	}
		
		/* Remove uneccessary styles loaded by parent theme */
		
		wp_dequeue_style( 'kleo-style' );
		wp_deregister_style( 'kleo-style' );
		
		wp_dequeue_style( 'kleo-app-css' );
		wp_deregister_style( 'kleo-app-css' );
		
		
		wp_dequeue_style( 'kleo-colors' );
		wp_deregister_style( 'kleo-colors' );
		

}
add_action( 'wp_enqueue_scripts', 'kino_register_styles', 25 );


function kino_register_scripts() {

	if ( is_single() ) { 
		if ( 'kino-admin' == get_post_type() ) {
					
					wp_enqueue_script( 'kino-admin-ajax', get_stylesheet_directory_uri() . '/js/kino-admin.js', array('jquery') );
					
					wp_localize_script( 'kino-admin-ajax', 'kino_ajax_object',
						array( 'ajax_url' => admin_url( 'admin-ajax.php' ) ) );
					
					
		}
	}

}
add_action( 'wp_enqueue_scripts', 'kino_register_scripts', 25 );

/**
 * Kleo Child Theme Functions
 * Add custom code below
*/ 

/*
    Add this into a custom plugin or your active theme's functions.php
*/


function kino_widgets_init() {
	register_sidebar( array(
		'name' => 'Checkout Widget Area',
		'id' => 'sidebar-40',
		'description' => 'Pour le texte en haut du formulaire de checkout pour un niveau d\'adhésion',
		'before_widget' => '<div id="%1$s" class="widget %2$s">',
		'after_widget' => '</div>',
		'before_title' => '<h3 class="widget-title">',
		'after_title' => '</h3>',
	) );
	register_sidebar( array(
		'name' => 'Tab Widget Area',
		'id' => 'sidebar-41',
		'description' => 'Le texte en haut du formulaire d\'édtion de profil',
		'before_widget' => '<div id="%1$s" class="widget %2$s">',
		'after_widget' => '</div>',
		'before_title' => '<h3 class="widget-title">',
		'after_title' => '</h3>',
	) );
	register_sidebar( array(
		'name' => 'Tab Bottom Widget Area',
		'id' => 'sidebar-42',
		'description' => 'Le texte en bas du formulaire d\'édtion de profil',
		'before_widget' => '<div id="%1$s" class="widget %2$s">',
		'after_widget' => '</div>',
		'before_title' => '<h3 class="widget-title">',
		'after_title' => '</h3>',
	) );
	
	register_sidebar( array(
		'name' => 'Tab Bottom Profil Kinoite',
		'id' => 'sidebar-48',
		'description' => 'Le texte en bas pour profil kinoite',
		'before_widget' => '<div id="%1$s" class="widget %2$s">',
		'after_widget' => '</div>',
		'before_title' => '<h3 class="widget-title">',
		'after_title' => '</h3>',
	) );
	
	register_sidebar( array(
		'name' => 'Tab Bottom Director Widget Area',
		'id' => 'sidebar-44',
		'description' => 'Le texte en bas pour réalisateurs',
		'before_widget' => '<div id="%1$s" class="widget %2$s">',
		'after_widget' => '</div>',
		'before_title' => '<h3 class="widget-title">',
		'after_title' => '</h3>',
	) );
	register_sidebar( array(
		'name' => 'Tab Bottom Comedian Widget Area',
		'id' => 'sidebar-45',
		'description' => 'Le texte en bas pour Comédien',
		'before_widget' => '<div id="%1$s" class="widget %2$s">',
		'after_widget' => '</div>',
		'before_title' => '<h3 class="widget-title">',
		'after_title' => '</h3>',
	) );
	register_sidebar( array(
		'name' => 'Tab Bottom Identity Widget Area',
		'id' => 'sidebar-46',
		'description' => 'Le texte en bas pour Identité',
		'before_widget' => '<div id="%1$s" class="widget %2$s">',
		'after_widget' => '</div>',
		'before_title' => '<h3 class="widget-title">',
		'after_title' => '</h3>',
	) );
	register_sidebar( array(
		'name' => 'Tab Bottom Technician Widget Area',
		'id' => 'sidebar-47',
		'description' => 'Le texte en bas pour artisans',
		'before_widget' => '<div id="%1$s" class="widget %2$s">',
		'after_widget' => '</div>',
		'before_title' => '<h3 class="widget-title">',
		'after_title' => '</h3>',
	) );


}
add_action( 'widgets_init', 'kino_widgets_init' );



/* admin interface
******************************/

require_once('functions/admin.php');

/* Code fore page header */

require_once('functions/header-output.php');

// require_once('functions/bp-fields.php');

require_once('functions/bp-user-fields.php');

require_once('functions/bp-group-tabs.php');

require_once('functions/bp-messages.php');

// require_once('functions/ajax.php');

/* 
kleo-google-fonts-css
*/

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
***************************************/

add_filter('login_redirect','kino_login_redirection',10,3);
// NOTE = this overrides the redirect url string!

function kino_login_redirection( $redirect_to, $request, $user ) {

		global $bp;
		//is there a user to check?
		global $user;
		
		if ( isset( $user->roles ) && is_array( $user->roles ) ) {
			
			if (function_exists('kino_user_participation')) {

				$kino_fields = kino_test_fields();
				$kino_user_role = kino_user_participation( $user->ID, $kino_fields );
				
				return bp_core_get_user_domain($user->ID);
				
				// Déjà inscrit au Kabaret?
//				if ( in_array( "kabaret-2016", $kino_user_role ) ) {
//					
//					// Aller à la section identité
//					return bp_core_get_user_domain($user->ID).'/profile/edit/group/10/';
//				
//				} 
				
			} else {
			
				// Pas de test?
				return $redirect_to;
			}
			
		} else { // user not defined
			
			// redirect them to the default place
			return $redirect_to;
			
		}

}



// Filter for kleo_title_section
// $args = apply_filters('kleo_title_args', $args);

add_filter('kleo_title_args', 'kino_title_filter',10,1);

function kino_title_filter( $args ) {
	
	if ( bp_is_user() ) {
	
		$title_content = $args['title'];
		
		$member_avatar = '<div class="item-avatar rounded kino-title-avatar">'. bp_get_member_avatar( array("class"  => "avatar kleo-rounded", "alt" => "") ) .'</div>';
		
		$args['title'] = $member_avatar . $title_content ;
		
		// ajouter le @username...
		
		$title_username = ' <span class="user-nicename">@'. bp_get_displayed_user_mentionname() .'</span>';
		
		$args['title'] .= $title_username;
		
		
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



/* Redirect after Avatar Upload */

// add_action( 'xprofile_avatar_uploaded', 'kino_avatar_uploaded' );

// function kino_avatar_uploaded() {
	
//	$kino_notifications = kino_edit_profile_notifications( bp_loggedin_user_id() );
//	
//	bp_core_add_message( $kino_notifications );
// 	bp_core_redirect( bp_core_get_userlink( bp_loggedin_user_id() ) ); // echo bp_core_get_userlink( bp_loggedin_user_id() );
// }


/* ACF options pages */

if( function_exists('acf_add_options_page') ) {
	
	acf_add_options_page();
	
}




