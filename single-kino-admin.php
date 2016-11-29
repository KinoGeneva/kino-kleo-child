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
						
						// Validation des Réalisateurs Kabaret
						
						if ( $kino_page_slug == 'validation-realisateurs' ) {
						
							get_template_part( 'kino-admin/validation-realisateurs' );
						
						// Validation Réalisateurs Palteforme
						
						} else if ( $kino_page_slug == 'validation-realisateurs-plateforme' ) {
						
							get_template_part( 'kino-admin/validation-realisateurs-plateforme' );
						
						// Inscription Au Kabaret
							
						} else if ( $kino_page_slug == 'inscriptions' ) {
						
							get_template_part( 'kino-admin/inscriptions' );
						
						// Gestion des Logements
							
						} else if ( $kino_page_slug == 'gestion-des-logements' ) {
							
							get_template_part( 'kino-admin/gestion-logements' );
						
						// Gestion des Bénévoles
							
						} else if ( $kino_page_slug == 'gestion-des-benevoles' ) {
							
							get_template_part( 'kino-admin/gestion-benevoles' );
							
						// Participants au Kabaret	
							
						} else if ( $kino_page_slug == 'participants-kabaret' ) {
							
							get_template_part( 'kino-admin/participants-kabaret' );
						
						// Membres hors-Kabaret	
							
						} else if ( $kino_page_slug == 'membres-hors-kabaret' ) {
							
							get_template_part( 'kino-admin/membres-hors-kabaret' );
						
						// Motivation Réalisateurs 
													
						} else if ( $kino_page_slug == 'motivation-realisateurs' ) {
							
							get_template_part( 'kino-admin/motivation-realisateurs' );
							
						} else if ( $kino_page_slug == 'debug' ) {
							
							get_template_part( 'kino-admin/debug' );
							
						} else if ( $kino_page_slug == 'sessions' ) {
							
							get_template_part( 'kino-admin/sessions' );
							
						} else if ( $kino_page_slug == 'vehicules-logements' ) {
							
							get_template_part( 'kino-admin/vehicules-logements' );
							
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

<?php get_footer();
