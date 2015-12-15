<?php 

// Masquer la barre admin pour les subscribers:
// source: http://wpbeg.in/Hg3djT

function kino_remove_admin_bar() {
	if ( current_user_can('subscriber') && !is_admin() ) {
  	show_admin_bar(false);
	}
}
add_action('after_setup_theme', 'kino_remove_admin_bar');

/**
 * Redirect back to homepage and not allow access to 
 * WP admin for Subscribers.
 */
function kino_redirect_admin(){
	if ( ! defined('DOING_AJAX') && current_user_can('subscriber') ) {
		wp_redirect( site_url() );
		exit;		
	}
}
add_action( 'admin_init', 'kino_redirect_admin' );


// WP User Groups 

// we don't need Types taxonomy, groups is enough

function kino_remove_stuff() {
 remove_action( 'init', 'wp_register_default_user_type_taxonomy' );
}
add_action( 'init' , 'kino_remove_stuff' , 0 );

// ajouter taxonomie "Logements"

function kino_register_logement_taxonomy() {

	new WP_User_Taxonomy( 'user-logement', 'users/logement', array(
		'singular' => 'Logement',
		'plural'   => 'Logements'
	) );
	
	// add_term_meta();	
}
add_action( 'init', 'kino_register_logement_taxonomy' );

// Ajouter deux TERM_META pour les logements:
/*

kino_logement_couchages
kino_logement_adresse

// {$taxonomy}_add_form_fields
// {$taxonomy}_edit_form_fields

*/


// For the add new form, we’ll use the following code:

// add_action( 'user-logement_add_form_fields', 'kino_new_term_meta', 10, 2 );

function kino_new_term_meta() {
    
		?>    
    <div class="form-field">
        <label for="kino_logement_couchages">Nombre de couchages</label>
        <input type="text" name="kino_logement_couchages" id="kino_logement_couchages" value="">
    </div>
<?php }

// The second function we need is the one that adds the HTML to our taxonomy Edit Term page. 

// Edit term page
// add_action( 'user-logement_edit_form_fields', 'kino_taxonomy_edit_meta_field', 10, 2 );

function kino_taxonomy_edit_meta_field($term) {
 
	// put the term ID into a variable
	$term_id = $term->term_id;
	$kino_meta_couchage = get_term_meta( $term_id, 'kino_logement_couchages', true );
	?>
	<tr class="form-field">
	<th scope="row" valign="top"><label for="kino_logement_couchages">Nombre de couchages</label></th>
		<td>
			<input type="text" name="kino_logement_couchages" id="kino_logement_couchages" value="<?php echo $kino_meta_couchage;  ?>">
			<p class="description">Entrer le nombre de couchages disponibles</p>
		</td>
	</tr>
	
<?php

}


// In order to save the fields we added, we need to hook into create_{$taxonomy} (new term form) and edit_{$taxonomy} (edit term form). Fortunately, we can use the same callback function with this and just check that our field was posted.

// add_action( 'edit_user-logement',   'kino_save_term_meta' );
// add_action( 'create_user-logement', 'kino_save_term_meta' );

function kino_save_term_meta( $term_id ) {

    $old_term_data = get_term_meta( $term_id, 'kino_logement_couchages', true );
    $new_term_data = isset( $_POST['kino_logement_couchages'] );

    if ( $old_term_data && '' === $new_term_data )
        delete_term_meta( $term_id, 'kino_logement_couchages' );

    else if ( $old_term_data !== $new_term_data )
        update_term_meta( $term_id, 'kino_logement_couchages', $new_term_data );

}


// Code from S.O.



?>