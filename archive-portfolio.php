<?php
/**
 * The template for displaying Portfolio Archive
 *
 * Used to display archive-type pages if nothing more specific matches a query.
 * For example, puts together date-based pages if no date.php file exists.
 *
 * If you'd like to further customize these archive views, you may create a
 * new template file for each specific one. For example, Twenty Fourteen
 * already has tag.php for Tag archives, category.php for Category archives,
 * and author.php for Author archives.
 *
 * @link http://codex.wordpress.org/Template_Hierarchy
 *
 * @package WordPress
 * @subpackage Kleo
 * @since Kleo 1.6.4
 */

get_header();

kleo_switch_layout( 'no' );
?>

<?php get_template_part('page-parts/general-title-section'); ?>

<?php get_template_part('page-parts/general-before-wrap'); ?>

	<?php if ( have_posts() ) : ?>
				
        <?php
        
        // Afficher filtre custom
        
        $terms = get_terms('portfolio-category'); // lesfilms
        
        kino_film_category_filter( 'archive-portfolio' );
        
        /* Uses page-parts/portfolio-masonry.php template */

        $columns = sq_option( 'portfolio_per_row', 4 );
        $display_type = sq_option( 'portfolio_style', 'default' );
        $title_style = sq_option( 'portfolio_title_style', 'default' );
        $show_filter = 'no'; // sq_option( 'portfolio_filter', 'yes' ); // 'no'; // 
        $excerpt = sq_option( 'portfolio_excerpt', '1' ) == '1' ? 'yes' : 'no';
        $img_width = '';
        $img_height = '';
        $image_size = sq_option( 'portfolio_image', '' );
        $img_array = explode( 'x', strtolower($image_size) );
        if (isset($img_array[1])) {
            $img_width = $img_array[0];
            $img_height = $img_array[1];
        }

        // echo kleo_portfolio_items( $display_type, $title_style, $columns, NULL, 'yes', $show_filter, $excerpt, $img_width, $img_height );
        
        // Start the Loop.
        
        ?>
        <div class="portfolio-wrapper">
        	<ul class="portfolio-items responsive-cols kleo-masonry  default-style per-row-4">
        <?php
        
        	while ( have_posts() ) : the_post();
        
        		/*
        		 * Include the post format-specific template for the content. If you want to
        		 * use this in a child theme, then include a file called called content-___.php
        		 * (where ___ is the post format) and that will be used instead.
        		 */
        
        		get_template_part( 'page-parts/portfolio-masonry' );
        
        	endwhile;
        	?>
        	
        		</ul></div>
        	
        		<?php
        		// page navigation.
        		kleo_pagination();
        ?>

    <?php endif; ?>


<?php get_template_part('page-parts/general-after-wrap'); ?>

<?php get_footer(); ?>