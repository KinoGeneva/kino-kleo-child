<?php
/**
 * @package WordPress
 */

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
	
//	$host = $_SERVER['HTTP_HOST'];
//	if ( $host == 'kinogeneva.4o4.ch' ) {
//	    wp_enqueue_style( 
//	    		'test-style', 
//	    		get_stylesheet_directory_uri() . '/css/test/00-testing.css', // main.css
//	    		false, // dependencies
//	    		null // version
//	    ); 
//	}
		
		/* Remove uneccessary styles loaded by parent theme */
		
		wp_dequeue_style( 'kleo-style' );
		wp_deregister_style( 'kleo-style' );
		
//		wp_dequeue_style( 'kleo-app-css' );
//		wp_deregister_style( 'kleo-app-css' );
		
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


