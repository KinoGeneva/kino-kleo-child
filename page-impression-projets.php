<?php
/**
 * Un template pour imprimer les projets de films
 */
?>
<?php $url = get_stylesheet_directory_uri(); ?>

<!doctype html>
<html class="no-js" lang="" moznomarginboxes mozdisallowselectionprint>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="x-ua-compatible" content="ie=edge">
        <title>Impression des projets de films</title>
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width, initial-scale=1">
		<meta name="robots" content="noindex, nofollow">
		<link href='https://fonts.googleapis.com/css?family=Roboto+Condensed:400,700|Roboto:500,300' rel='stylesheet' type='text/css'>
		<link rel="stylesheet" href="<?php echo $url ?>/css/dev/30-print.css">

    </head>
    <body>
		<!-- Begin Article -->
		<article>
			<div class="article-content">
			
			<?php if ( bp_has_groups() ) : while ( bp_groups() ) : bp_the_group(); ?>
			
			<?php
			//meta info, id du groupe et id de l'article associé
			$group_id = bp_get_group_id(); 
			$fiche_projet_post_id = groups_get_groupmeta($group_id, 'fiche-projet-post-id');
			?>			
			<div class="profile clearfix print-profile">
					<h1><?php bp_group_name(); ?></h1>

		<div class="center">Un film de <span class="strong">
		<?php 
		$id_real = get_field('realisateur', $fiche_projet_post_id)['ID'];
		echo bp_core_get_user_displayname($id_real) .' | ';
		the_field('duree', $fiche_projet_post_id); ?> | <?php the_field('genre', $fiche_projet_post_id); 
		echo '</span></div>';

		//obtenir la session automatiquement
		$sessions_terms = get_terms( array(
			'taxonomy' => 'user-group',
			'name__like' => 'session' ,
			'fields' => 'names',
		) );
		$user_terms = wp_get_object_terms( $id_real , 'user-group', array('fields' => 'names'));
		
		echo '<h3 class="red">'. current( ( array_intersect( $sessions_terms,$user_terms ) ) ) .'</h3>';
		?>
		
		<hr/>
		
		<h3>Synopsis</h3>
		<?php bp_group_description() ?>

			<h3>Équipe</h3>
			<div class="center">
		<?php
		#les membres selon la fiche projet : # plateforme (on stock l'identifiant) + # non plateforme (on stock le nom renseigné)
		$projet_members= array();
		if( have_rows('equipe', $fiche_projet_post_id) ){
			while ( have_rows('equipe', $fiche_projet_post_id) )  {
				the_row();
				echo '<b>'. get_sub_field('role', $fiche_projet_post_id) .' | </b>';

				if(get_sub_field('membre_kino_kabaret_2017', $fiche_projet_post_id)){
					echo bp_core_get_username( get_sub_field('membre_kino_kabaret_2017', $fiche_projet_post_id)['ID'] );
					
				}
				else if(get_sub_field('membre_hors_plateforme', $fiche_projet_post_id)){
					echo get_sub_field('membre_hors_plateforme', $fiche_projet_post_id);
				}
				echo '   ';
			}
		}
		//print_r($projet_members);
		
	?>
		</div>
		<h2>Tournage</h2>

			<?php
		if( have_rows('lieux_de_tournage', $fiche_projet_post_id) ){
			while ( have_rows('lieux_de_tournage', $fiche_projet_post_id) )  {
				the_row();
				echo '<div class="boxcal">';
				echo '<div class="strong">'. get_sub_field('jours', $fiche_projet_post_id) .', de ';
				the_sub_field('tournage_debut', $fiche_projet_post_id);
				echo ' à ';
				the_sub_field('tournage_fin', $fiche_projet_post_id);
				echo '</div>';
				the_sub_field('adresse', $fiche_projet_post_id);
				echo '</div>';
			}
		}
		?>

<hr/>
<div class="center">
		<h3 class="red">Besoins pour le film</h3>

		<?php
		if(get_field('besoin_equipe', $fiche_projet_post_id)){
			echo '<h4 class="red">Équipe</h4>
			<div>';
			foreach(get_field('besoin_equipe', $fiche_projet_post_id) as $need){
				echo $need .', ';
			}
			echo '</div>';
		}
		?>

		<?php
		if( have_rows('besoin_comediens', $fiche_projet_post_id) ){
			echo '<h4 class="red">Casting</h4>
			<div>';
			while ( have_rows('besoin_comediens', $fiche_projet_post_id) )  {
				the_row();
				$casting = array();
				if(get_sub_field('besoin_comedien_sexe', $fiche_projet_post_id) && get_sub_field('besoin_comedien_sexe', $fiche_projet_post_id)!='Indifférent'){
					$casting[] = get_sub_field('besoin_comedien_sexe', $fiche_projet_post_id);
				}

				if(get_sub_field('besoin_comedien_age_minimum', $fiche_projet_post_id) && get_sub_field('besoin_comedien_age_maximum', $fiche_projet_post_id)) {
					$casting[] = 'âge caméra de '. get_sub_field('besoin_comedien_age_minimum', $fiche_projet_post_id) .' à '. get_sub_field('besoin_comedien_age_maximum', $fiche_projet_post_id) .' ans';
				}
				
				if(get_sub_field('besoin_comedien_cheveux', $fiche_projet_post_id) && get_sub_field('besoin_comedien_cheveux', $fiche_projet_post_id)!='Indifférent'){
					$casting[] = 'cheveux '. get_sub_field('besoin_comedien_cheveux', $fiche_projet_post_id);
				}
				
				if(get_sub_field('besoin_comedien_yeux', $fiche_projet_post_id) && get_sub_field('besoin_comedien_yeux', $fiche_projet_post_id)!='Indifférent'){
					$casting[] = 'Yeux '. get_sub_field('besoin_comedien_yeux', $fiche_projet_post_id);
				}
				
				if(get_sub_field('besoin_comedien_langues_jouees', $fiche_projet_post_id)){
					$casting[] = 'parlant '. implode(' + ', get_sub_field('besoin_comedien_langues_jouees', $fiche_projet_post_id));
				}
				
				if(get_sub_field('besoin_comedien_talents', $fiche_projet_post_id)){
					$casting[] = implode(', ', get_sub_field('besoin_comedien_talents', $fiche_projet_post_id));
				}
				
				//display casting
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
				echo '<br/>';
			}
			echo '</div>';
		}
		?>

		<?php
		if(get_field('casting_et_direction_acteur', $fiche_projet_post_id)) {
			echo '<h4 class="red">Casting et direction d\'acteur</h4>';
		}
		?>
		
		<?php 
		if(get_field('besoin_lieux_de_tournage', $fiche_projet_post_id)){
			echo '<h4 class="red">Lieux de tournage</h4><div>';
			the_field('besoin_lieux_de_tournage', $fiche_projet_post_id);
			echo '</div>';
		}
		?>
		 
		 <?php 
		if(get_field('besoin_accessoires', $fiche_projet_post_id)){
			echo '<h4 class="red">Accessoires</h4><div>';
			the_field('besoin_accessoires', $fiche_projet_post_id);
			echo '</div>';
		}
		 ?>
		
		 <?php 
		if(get_field('besoin_costumes', $fiche_projet_post_id)){
			echo '<h4 class="red">Costumes</h4><div>';
			the_field('besoin_costumes', $fiche_projet_post_id);
			echo '</div>';
		}
		 ?>

		
			<?php
			//besoin_maquillage
			if(get_field('besoin_maquillage', $fiche_projet_post_id)) {
				echo '<h4 class="red">Maquillage</h4>
				<div>';
				if(get_field('maquillage_1', $fiche_projet_post_id)) {
					echo get_field('maquillage_1', $fiche_projet_post_id) .' comédien(s) | naturel | PAT '. get_field('jour_et_horaire_pat_1', $fiche_projet_post_id) .'<br/>';
				}
				if(get_field('maquillage_2', $fiche_projet_post_id)) {
					echo get_field('maquillage_2', $fiche_projet_post_id) . ' comédien(s) | soutenu | PAT '. get_field('jour_et_horaire_pat_2', $fiche_projet_post_id) .'<br/>';
				}
				if(get_field('maquillage_3', $fiche_projet_post_id)) {
					echo get_field('maquillage_3', $fiche_projet_post_id) .' comédien(s) | vieillir | PAT '. get_field('jour_et_horaire_pat_3', $fiche_projet_post_id) .'<br/>';
				}
				if(get_field('maquillage_4', $fiche_projet_post_id)) {
					echo get_field('maquillage_4', $fiche_projet_post_id) .' comédien(s) | FX | PAT '. get_field('jour_et_horaire_pat_4', $fiche_projet_post_id) .'<br/>';
				}
				echo '</div>';
			}
			?>
	
			<?php
			if(get_field('besoin_coiffure', $fiche_projet_post_id)) {
				echo '<h4  class="red">Coiffure</h4>';
				echo '<div>'. get_field('coiffure_nombre_de_comedien', $fiche_projet_post_id) .' comédien(s) | coiffure type '. get_field('type_de_coiffure', $fiche_projet_post_id) .' | PAT: '. get_field('coiffure_jour_et_horaire_pat', $fiche_projet_post_id) .'<br/></div>';
			}
			?>
			</div>
			</div>
			 <div class="page-break"></div>
			<?php endwhile; endif; ?>
			
			</div><!--end article-content-->

		</article>
		<!-- End  Article -->

    </body>
</html>