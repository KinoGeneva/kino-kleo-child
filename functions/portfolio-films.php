<?php 


/*
 * Remplacer le slug des "portfolio-category" par "lesfilms".
 * 
 * On utilise le filtre fourni par Kleo:
 * kleo_portfolio_cat_slug
 * original: portfolio-category
 * new: 
*/

function kino_portfolio_cat_slug( $slug ) {	

	// return 'portfolio-category';
	return 'lesfilms';

}

add_filter( 'kleo_portfolio_cat_slug', 'kino_portfolio_cat_slug' );



// Register Custom Taxonomy
function kino_taxonomy_lesfilms() {

	$labels = array(
		'name'                       => _x( 'Film Categories', 'Taxonomy General Name', 'text_domain' ),
		'singular_name'              => _x( 'Film Category', 'Taxonomy Singular Name', 'text_domain' ),
		'menu_name'                  => __( 'Film Categories', 'text_domain' ),
		'all_items'                  => __( 'All Items', 'text_domain' ),
		'parent_item'                => __( 'Parent Item', 'text_domain' ),
		'parent_item_colon'          => __( 'Parent Item:', 'text_domain' ),
		'new_item_name'              => __( 'New Item Name', 'text_domain' ),
		'add_new_item'               => __( 'Add New Item', 'text_domain' ),
		'edit_item'                  => __( 'Edit Item', 'text_domain' ),
		'update_item'                => __( 'Update Item', 'text_domain' ),
		'view_item'                  => __( 'View Item', 'text_domain' ),
		'separate_items_with_commas' => __( 'Separate items with commas', 'text_domain' ),
		'add_or_remove_items'        => __( 'Add or remove items', 'text_domain' ),
		'choose_from_most_used'      => __( 'Choose from the most used', 'text_domain' ),
		'popular_items'              => __( 'Popular Items', 'text_domain' ),
		'search_items'               => __( 'Search Items', 'text_domain' ),
		'not_found'                  => __( 'Not Found', 'text_domain' ),
		'no_terms'                   => __( 'No items', 'text_domain' ),
		'items_list'                 => __( 'Items list', 'text_domain' ),
		'items_list_navigation'      => __( 'Items list navigation', 'text_domain' ),
	);
	$args = array(
		'labels'                     => $labels,
		'hierarchical'               => true,
		'public'                     => true,
		'show_ui'                    => true,
		'show_admin_column'          => true,
		'show_in_nav_menus'          => true,
		'show_tagcloud'              => true,
	);
	// register_taxonomy( 'lesfilms', array( '' ), $args );

}
add_action( 'init', 'kino_taxonomy_lesfilms', 0 );

/*
 * Fonction pour afficher filtre
 * sur les pages Archives de Films
*/

function kino_film_category_filter( $currentpage ) {
	
	$terms = get_terms( array(
	    'taxonomy' => 'portfolio-category',
	    'hide_empty' => true,
	    'exclude' => array(321)
	) );
	
	/*
	 * Note : problème masquage articles brouillons:
	 * Cf https://wordpress.stackexchange.com/questions/225458/get-terms-showing-link-to-category-even-if-all-posts-are-drafts
	 * get_terms() doesn't have built-in feature that excludes draft posts because it keeps track of only total posts term is attached to.
	 
	 * Workaround: on exclut temporairement "Kinogeneva 2017" (id 321)
	*/
	
	if ( $currentpage == 'archive-portfolio' ) {
		$classeactive = ' selected';
	} else {
		$classeactive = '';
	}
	
	$filter = '<div class="row clearfix">';
	$filter .= '<ul class="portfolio-filter-tabs bar-styling col-sm-12 clearfix">';
	$filter .= '<li class="all'.$classeactive.'"><a href="/films/">Tout</a></li>';

	foreach ( $terms as $term) {
		
		// ajouter classe "selected" si page active	
		
		if ( $currentpage == $term->slug ) {
			$classeactive = ' selected';
		} else {
			$classeactive = '';
		}
			
		$filter .= '<li class="'.$classeactive.'"><a href="/lesfilms/' . $term->slug .'">';
		
		$filter .= $term->name ;
		 
	  $filter .= '</a></li>';
	   
	}
			
	$filter .= '</ul></div>';
	echo $filter;
}