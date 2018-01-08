<?php
/* dépend de buddypress, bp-groups
 * affiche les projets (bp-groups) et leurs besoins (post)
 * copie et modif du template de kleo-child > buddypress > groups > groups-loop.php */

get_header(); ?>

<?php get_template_part('page-parts/general-title-section'); ?>

<?php get_template_part('page-parts/general-before-wrap'); ?>

<div id="buddypress">
<div id="groups-dir-list" class="groups dir-list">

<?php
//recherche des projets et de la valeur meta du portfolio associé
$portfolio_ids = array();

if ( bp_has_groups( bp_ajax_querystring( 'groups' ). '&per_page=100' ) ) {
	while ( bp_groups() ) {
		bp_the_group();
		$group_id = bp_get_group_id();
				
		if($portfolio_id = groups_get_groupmeta($group_id, 'fiche-projet-post-id')){
			$portfolio_ids[$group_id] = $portfolio_id;
		}
	 }
}

//affiche les filtres de recherche

echo '<h3>Filtres de recherches</h3>';

get_template_part('page-parts/besoins-search');


$feed_back = '';

//test sur la recherche de l'utilisateur
//la recherche dans la BDD avec un meta_query sur un grand nombre de données meta etant trop lourde, on test d'abord les différents champs pour sélectionner les ids à rechercher dans la BDD
$include_portfolio_ids = array();
if(	!empty( $_POST ) ){
	/*
	echo '<pre>';
	print_r($_POST);
	echo '</pre>';
	*/
	foreach( $portfolio_ids as $group_id => $portfolio_id){
		//get ACF data
		$portfolio_fields = get_fields($portfolio_id);
		/*
		echo '<pre>';
		print_r($portfolio_fields);
		echo '</pre>';
		*/
		//recherche de besoin en équipe
		if( !empty( $_POST['besoin_equipe'] ) ){
			if(!empty($portfolio_fields['besoin_equipe'])  && in_array($_POST['besoin_equipe'], $portfolio_fields['besoin_equipe'])){
				$include_portfolio_ids[$group_id] = $portfolio_id;
			}
		}
		
		//recherche de besoin en comédien 
		if( !empty($_POST['besoin_comedien_sexe']) || !empty($_POST['besoin_comedien_age_minimum']) || !empty($_POST['besoin_comedien_age_maximum'])) {
			if(!empty($portfolio_fields['besoin_comediens'] ) ) {
				foreach( $portfolio_fields['besoin_comediens'] as $key => $values){
					//sexe
					if(!empty($_POST['besoin_comedien_sexe'])){
						if( $_POST['besoin_comedien_sexe'] == $values['besoin_comedien_sexe'] ) {
							$include_portfolio_ids[$group_id] = $portfolio_id;
						}
					}
					//age
					if(!empty($_POST['besoin_comedien_age_minimum']) && !empty($_POST['besoin_comedien_age_maximum'])){
						if(( $_POST['besoin_comedien_age_minimum'] >= $values['besoin_comedien_age_minimum'] ||  empty($values['besoin_comedien_age_minimum']) ) && ($_POST['besoin_comedien_age_maximum'] <= $values['besoin_comedien_age_maximum']  || empty($values['besoin_comedien_age_maximum']) )) {
							$include_portfolio_ids[$group_id] = $portfolio_id;
							}
					}
					else {
						//age minimum
						if( !empty($_POST['besoin_comedien_age_minimum']) ){
							if( $_POST['besoin_comedien_age_minimum'] >= $values['besoin_comedien_age_minimum'] ||  empty($values['besoin_comedien_age_minimum'])) {
								$include_portfolio_ids[$group_id] = $portfolio_id;
							}
						}
						//age maximum
						if(!empty($_POST['besoin_comedien_age_maximum'])) {
							if( $_POST['besoin_comedien_age_maximum'] <= $values['besoin_comedien_age_maximum']  || empty($values['besoin_comedien_age_maximum']) ){
								$include_portfolio_ids[$group_id] = $portfolio_id;
							}
						}
					}
				}
			}
		}
		
		//'casting_et_direction_acteur'
		if(!empty( $_POST['casting_et_direction_acteur'] )){
			if( !empty($portfolio_fields['casting_et_direction_acteur'] ) ){
				if($_POST['casting_et_direction_acteur'] == $portfolio_fields['casting_et_direction_acteur']){
					$include_portfolio_ids[$group_id] = $portfolio_id;
				}
			}
		}
		
		//'besoin_maquillage'
		if( !empty( $_POST['besoin_maquillage'] ) ){
			if( !empty($portfolio_fields['besoin_maquillage']) && $_POST['besoin_maquillage'] == $portfolio_fields['besoin_maquillage']){
				$include_portfolio_ids[$group_id] = $portfolio_id;
			}
		}
		
		//'besoin_coiffure'
		if( !empty( $_POST['besoin_coiffure'] ) ){
			if(!empty($portfolio_fields['besoin_coiffure']) && $_POST['besoin_coiffure'] == $portfolio_fields['besoin_coiffure']){
				$include_portfolio_ids[$group_id] = $portfolio_id;
			}
		}
	}
	//fin de la recherche POST: y a t'il des portfolio qui correspondent?
	if(empty($include_portfolio_ids)){
		$feed_back.= "Aucun projet n'a les besoins que vous avez sélectionnés. ";
		//$include_portfolio_ids = $portfolio_ids;
	}
}
if(empty($include_portfolio_ids)){
	$include_portfolio_ids = $portfolio_ids;
}

//récupère les portfolios qui sont associés à un projet (BP group) /qui correspondent à la recherche de l'utilisateur/ et qui sont en mode "draft" (= édition en cours, film non publié)
$projets_portfolio = array();
if(!empty($include_portfolio_ids)){
	$projets_portfolio = get_posts(array(
		'posts_per_page'	=> -1,
		'post_type'			=> 'portfolio',
		'post_status'		=> 'draft',
		'include' => $include_portfolio_ids,
	));
	if(empty($projets_portfolio)){
		$feed_back.=  "Aucun projet en cours. ";
	}
	else {
		$feed_back.= 'Affichage de '. count($projets_portfolio) .' projets. ';
	}
}

//template groups-loop.php
?>

<div class="pagination">
	<div class="pag-count" id="group-dir-count-top">
		<?php
		if(isset($feed_back)){
			echo $feed_back;
		}
		?>
		
	</div>
</div>

<ul id="groups-list" class="item-list kleo-isotope masonry">

<?php

//affiche les projets
foreach ( $projets_portfolio as $portfolio ) {

	setup_postdata( $portfolio );

	$portfolio_id = $portfolio->ID;
	$group_id = array_search( $portfolio_id , $include_portfolio_ids );
	//echo '<h3>'. $group_id .'</h3>';

	
	$portfolio_fields = get_fields($portfolio_id);
	
	get_template_part('page-parts/besoins-single-projet');
	//echo 'projet affiché';
	wp_reset_postdata();
}
?>
</ul>

<script>
jQuery(document).ready(function($){	
		//toogle
		$('.toogleneed').next().hide();
		$('.toogleneed').click(function() {
			$(this).next().toggle(200);
			
			//effacer les champs
			$(':input','#formneed')
			  .removeAttr('checked')
			  .removeAttr('selected')
			  .not(':button, :submit, :reset, :hidden, :radio, :checkbox')
			  .val('');
			  
			return false;
        
    });
});
</script>

</div></div>

<?php get_template_part('page-parts/general-after-wrap'); ?>

<?php get_footer(); ?>
