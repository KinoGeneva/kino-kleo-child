<?php
/**
 * The template for displaying all pages
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages and that
 * other 'pages' on your WordPress site will use a different template.
 *
 * @package Wordpress
 * @subpackage Kleo
 * @since Kleo 1.0
 */

get_header(); ?>

<?php get_template_part('page-parts/general-title-section'); ?>

<?php get_template_part('page-parts/general-before-wrap'); ?>

<?php 
//recherche de tous les groupes associés à un article
if ( bp_has_groups()) {
	$fiche_projet_groups_id = array();
	$include_post_ids = array();
	while ( bp_groups() ) {
		bp_the_group();
		$groupid = bp_get_group_id();
		//echo 'test:'. bp_get_group_id() .'<br/>';
		$fiche_groupe_id = groups_get_groupmeta($groupid, 'fiche-projet-post-id');
		if($fiche_groupe_id){
			//$fiche_projet_groups_id[$fiche_groupe_id] = bp_get_group_id();
			$include_post_ids[$groupid] = $fiche_groupe_id;
		}
	 }
	 //print_r($include_post_ids);
}

//tous les articles qui sont associés à un projet (BP group) 
$projets_post = get_posts(array(
	'posts_per_page'	=> -1,
	'post_type'			=> 'post',
	'include' => $include_post_ids
	//'meta_query' => $meta_query 
));

/*
echo '<pre>';
print_r($projets_post);
echo '</pre>';
*/

if ( $projets_post ) {
$p = 1;
    foreach ( $projets_post as $post ) {
        setup_postdata( $post ); 

		//$groupid = array_search( $post->ID , $include_post_ids );
		
		//une ligne tous les 2 projets
		if($p % 2 ==1){
			echo '<div class="row">';
		}
		?>
		
		<div class="col-sm-6 projet" style="border-left: 1px solid #E5E5E5; margin-bottom: 20px;">		

		<?php
		/*
		$project_values = array();
		$project_values = get_post_meta( $post->ID );
		* */
		?>
		
		<h2><?php the_title(); ?></a></h2>
		Un film de <span class="strong">
		<?php 
		$id_real = get_field('realisateur')['ID'];
		echo bp_core_get_user_displayname($id_real) .' | ';
		the_field('duree'); ?> | <?php the_field('genre'); 
		echo '</span>';
		
		//obtenir la session automatiquement
		$sessions_terms = get_terms( array(
			'taxonomy' => 'user-group',
			'name__like' => 'session' ,
			'fields' => 'names',
		) );
		$user_terms = wp_get_object_terms( $id_real , 'user-group', array('fields' => 'names'));
		
		echo '<h3 class="red">'. current( ( array_intersect( $sessions_terms,$user_terms ) ) ) .'</h3>';
		?>
		
		
		<?php
		if(get_field('besoin_equipe')){
			echo '<h4>Équipe</h4>
			<div><ul>';
			foreach(get_field('besoin_equipe') as $need){
				echo '<li>'. $need .'</li>';
			}
			echo '</ul>
			</div>';
		}
		?>

		<?php
		if( have_rows('besoin_comediens') ){
			echo '<h4>Casting</h4>
			<div><ul>';
			while ( have_rows('besoin_comediens') )  {
				the_row();
				$casting = array();
				if(get_sub_field('besoin_comedien_sexe') && get_sub_field('besoin_comedien_sexe')!='Indifférent'){
					$casting[] = get_sub_field('besoin_comedien_sexe');
				}

				if(get_sub_field('besoin_comedien_age_minimum') && get_sub_field('besoin_comedien_age_maximum')) {
					$casting[] = 'âge caméra de '. get_sub_field('besoin_comedien_age_minimum') .' à '. get_sub_field('besoin_comedien_age_maximum') .' ans';
				}
				
				if(get_sub_field('besoin_comedien_cheveux') && get_sub_field('besoin_comedien_cheveux')!='Indifférent'){
					$casting[] = 'cheveux '. get_sub_field('besoin_comedien_cheveux');
				}
				
				if(get_sub_field('besoin_comedien_yeux') && get_sub_field('besoin_comedien_yeux')!='Indifférent'){
					$casting[] = 'Yeux '. get_sub_field('besoin_comedien_yeux');
				}
				
				if(get_sub_field('besoin_comedien_langues_jouees')){
					$casting[] = 'parlant '. implode(' + ', get_sub_field('besoin_comedien_langues_jouees'));
				}
				
				if(get_sub_field('besoin_comedien_talents')){
					$casting[] = implode(', ', get_sub_field('besoin_comedien_talents'));
				}
				
				//display casting
				echo '<li>';
				foreach($casting as $n => $need){
					$need = strtolower($need);
					if($n == 0){
						echo ucfirst($need);
					}
					else {
						echo $need;
					}
					if($n < (count($casting)-1)){
						echo ', ';
					}
				}
				echo '</li>';
			}
			echo '</ul></div>';
		}
		?>

		<?php
		if(get_field('casting_et_direction_acteur')) {
			echo "<h4>Casting et direction d'acteur</h4>";
		}
		?>
		
		<?php 
		if(get_field('besoin_lieux_de_tournage')){
			echo '<h4>Lieux de tournage</h4><div>';
			the_field('besoin_lieux_de_tournage');
			$images = get_field('besoin_lieux_de_tournage_photos');

			if( $images ) {
				foreach( $images as $image ) {
					echo '
					<div class="image">
						<a href="'. $image['url'] .'">
							 <img src="'. $image['sizes']['thumbnail'] .'" alt="'. $image['alt'] .'" />
						</a>
					</div>';
				}

			}
			echo '<div style="clear: both"></div></div>';
		}
		?>
		 
		 <?php 
		if(get_field('besoin_accessoires')){
			echo '<h4>Accessoires</h4><div>';
			the_field('besoin_accessoires');
			echo '</div>';
		}
		 ?>
		
		 <?php 
		if(get_field('besoin_costumes')){
			echo '<h4>Costumes</h4><div>';
			the_field('besoin_costumes');
			echo '</div>';
		}
		 ?>

		
			<?php
			//besoin_maquillage
			if(get_field('besoin_maquillage')) {
				echo '<h4>Maquillage</h4>
				<div><ul>';
				if(get_field('maquillage_1')) {
					echo '<li>'. get_field('maquillage_1') .' comédien(s) | naturel | PAT '. get_field('jour_et_horaire_pat_1') .'</li>';
				}
				if(get_field('maquillage_2')) {
					echo '<li>'. get_field('maquillage_2') . ' comédien(s) | soutenu | PAT '. get_field('jour_et_horaire_pat_2') .'</li>';
				}
				if(get_field('maquillage_3')) {
					echo '<li>'. get_field('maquillage_3') .' comédien(s) | vieillir | PAT '. get_field('jour_et_horaire_pat_3') .'</li>';
				}
				if(get_field('maquillage_4')) {
					echo '<li>'. get_field('maquillage_4') .' comédien(s) | FX | PAT '. get_field('jour_et_horaire_pat_4') .'</li>';
				}
				echo '</ul></div>';
			}
			?>
	
			<?php
			if(get_field('besoin_coiffure')) {
				echo '<h4>Coiffure</h4>';
				echo '<div>'. get_field('coiffure_nombre_de_comedien') .' comédien(s) | coiffure type '. get_field('type_de_coiffure') .' | PAT: '. get_field('coiffure_jour_et_horaire_pat') .'<br/></div>';
			}
			?>
		</div>
			
		<?php
		//une ligne tous les 2 projets
		if($p % 2 ==0){
			echo '</div>';
		}
		$p++;
	}
	wp_reset_postdata();
}
?>


<?php get_template_part('page-parts/general-after-wrap'); ?>

<?php get_footer(); ?>
