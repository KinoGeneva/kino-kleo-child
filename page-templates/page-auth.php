<?php
/**
 * Template Name: Kinoites Only
 *
 * Description: Template withour sidebar
 *
 * @package WordPress
 * @subpackage Kleo
 * @author SeventhQueen <themesupport@seventhqueen.com>
 * @since Kleo 1.0
 */

get_header(); ?>
	
<?php get_template_part('page-parts/general-title-section'); ?>

<?php get_template_part('page-parts/general-before-wrap'); ?>

<?php if ( have_posts() ) : ?>
    <?php
	// Start the Loop.
	while ( have_posts() ) : the_post();
    
     if ( is_user_logged_in() ) {   
        
		/*
		 * Include the post format-specific template for the content. If you want to
		 * use this in a child theme, then include a file called called content-___.php
		 * (where ___ is the post format) and that will be used instead.
		 */
		get_template_part( 'content', 'page' );
        ?>

        <?php get_template_part( 'page-parts/posts-social-share' ); ?>

        <?php if ( sq_option( 'page_comments', 0 ) == 1 ): ?>

            <!-- Begin Comments -->
            <?php comments_template( '', true ); ?>
            <!-- End Comments -->

        <?php endif; ?>
	
	<?php 
	
	} else {
						
							?>
							
							<div class="post-header">
								    <h1 class="post-title"><?php the_title(); ?></h1>
								    				    
							    </div> <!-- /post-header -->
								
								<div class="post-content">
									<p>Veuillez <a href="<?php echo wp_login_url( get_permalink().'?version=10923482' ); ?>" title="Login">vous connecter avec votre login</a> pour accéder à cette page.</p>
									<div class="clear"></div>
								</div> <!-- /post-content -->
								
							<?php
						
						}
	
	 ?>
	

	<?php endwhile; ?>

<?php endif; ?>
        
<?php get_template_part('page-parts/general-after-wrap'); ?>

<?php get_footer(); ?>