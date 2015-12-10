<?php
/**
 * Tempalate for Kino Admin post type
 *
 * Based on Pages
 *
 * @package Wordpress
 * @subpackage Kleo
 * @since Kleo 1.0
 */

get_header(); ?>

<?php get_template_part('page-parts/general-title-section'); ?>

<?php get_template_part('page-parts/general-before-wrap'); ?>

<?php if ( have_posts() ) : ?>
    <?php
	// Start the Loop.
	while ( have_posts() ) : the_post();
    ?>
        <?php
		
		// Test conditionnel pour include un template spécifique
		
		// Accessible pour: Editor ou Administrator 
		
		if ( current_user_can( 'publish_pages' ) ) {
		
						$kino_page_slug = $post->post_name;
						
						if ( $kino_page_slug == 'validation-realisateurs' ) {
						
							get_template_part( 'kino-admin/validation-realisateurs' );
						
						} else if ( $kino_page_slug == 'impression-fiches' ) {
							
							get_template_part( 'kino-admin/impression-fiches' );
							
						} else {
								
							get_template_part( 'content', 'page' );
						
						}
		
		} else {
		
			echo '<h3>Désolé, l’accès à cette page est réservé aux administrateurs-trices de Kino Geneva!</h3>';
		
		}
		
    ?>

	<?php endwhile; ?>

<?php endif; ?>
        
<?php get_template_part('page-parts/general-after-wrap'); ?>

<?php get_footer(); ?>