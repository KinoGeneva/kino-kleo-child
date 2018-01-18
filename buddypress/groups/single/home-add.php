<?php

//meta info, id du groupe et id de l'article associé
$group_id = bp_get_group_id(); 
$fiche_projet_post_id = groups_get_groupmeta($group_id, 'fiche-projet-post-id');

$url = home_url();
?>

<script type="text/javascript">
	$(document).ready(function(){
		//toogle menu
		$('.besoins h4').next().show();
		$('.besoins h4').click(function() {
			$(this).toggleClass('closeit');
			$(this).next().toggle(200);
			return false;
		});
	});
</script>
<div class="row">
	<div class="col-sm-8 projet">

		<h1><?php bp_group_name(); ?></h1>

		Un film de <span class="strong">
		<?php 
		$id_real = get_field('realisateur', $fiche_projet_post_id)['ID'];
		echo bp_core_get_user_displayname($id_real) .' | ';
		the_field('duree', $fiche_projet_post_id); ?> | <?php the_field('genre', $fiche_projet_post_id); 
		echo '</span>';

		$sessions = wp_get_object_terms( $group_id , 'bp_group_tags', array('fields' => 'names') );
		
		echo '<h3 class="red">';
		foreach($sessions as $session){
			echo $session .' ';
		}
		echo '</h3>';
		?>
		
		<h3>Le réalisateur</h3>
		<div class="item-avatar rounded">
			  <?php echo get_avatar($id_real,80); ?>
		</div>
		<?php
		echo '<h4 class="no-color">'. bp_core_get_userlink($id_real) .'</h4>';
		//montre seulement les infos de contacts aux personnes connectées
		if ( is_user_logged_in() ) {
			xprofile_get_field_data('e-mail', $id_real) .'<br/>'. xprofile_get_field_data('Téléphone', $id_real);
		}
		?>
		
		<div style="clear: both;"></div>
		
		<div id="item-buttons" style="text-align: right;">

		<?php do_action( 'bp_group_header_actions' ); ?>

		</div><!-- #item-buttons -->
		
		<hr/>
		<h3>Synopsis</h3>
		<?php bp_group_description() ?>

		<hr/>
		<h3>Équipe</h3>

		<?php
		#les membres selon la fiche projet : plateforme  + non plateforme
		$projet_members = array();
		
		#les comédiens
		//plateforme
		if($portfolio_members = get_field('comedien-nes', $fiche_projet_post_id) ){
			foreach($portfolio_members as $portfolio_member) {
				$kino_member_id = $portfolio_member['ID'];
				$kino_member = get_user_by('id', $kino_member_id);
				
				$member_display = '<a href="'. $url .'/members/'. $kino_member->user_nicename .'/" target="_blank">'. $kino_member->display_name .'</a>';
				$projet_members['Comédiens'][] = $member_display;
			}
		}
		//non plateforme
		if( $member_display = wp_strip_all_tags(get_field('autres_comediens', $fiche_projet_post_id) )){
			$projet_members['Comédiens'][] = $member_display;
		}
		
		//les techniciens
		if( have_rows('equipe', $fiche_projet_post_id) ){
			while ( have_rows('equipe', $fiche_projet_post_id) )  {
				the_row();
				if($portfolio_member = get_sub_field('membre_kino_kabaret_2017', $fiche_projet_post_id)){
					$kino_member_id = $portfolio_member['ID'];
					$kino_member = get_user_by('id', $kino_member_id);
					$member_display = '<a href="'. $url .'/members/'. $kino_member->user_nicename .'/" target="_blank">'. $kino_member->display_name .'</a>';
					//role
					if(!$role = get_sub_field('role', $fiche_projet_post_id) ){
						$role = '-';
					}
				}
				//les participants non membres
				elseif($member_display = wp_strip_all_tags(get_sub_field('membre_hors_plateforme', $fiche_projet_post_id))){
					//role
					if(!$role = get_sub_field('role', $fiche_projet_post_id) ){
						$role = '-';
					}
				}
				$projet_members[$role][] = $member_display;
				
			}
		}		
		
		#affichage
		$display_members = '';
		foreach( $projet_members as $role => $members) {
			$display_members.= '<b>'. $role .' | </b>';
			foreach($members as $member){
				$display_members.= '<span class="kp-pointlist">'. $member .'</span>';
			}
			$display_members.= '<br/>';
		}
		echo $display_members;
		?>

		<h4 class="red">Membres de la communauté intéressés par le projet</h4>
					
		<?php
		$args = array(
			'group_id' => bp_get_group_id(),
			'exclude_admins_mods'=> 0
		); 
		if ( bp_group_has_members( $args ) ) {
			while ( bp_group_members( ) ) {
				bp_group_the_member();
				?>
				 <div class="item-avatar rounded">
				  <a href="<?php bp_member_permalink(); ?>"><?php bp_member_avatar('type=full&width=40&height=40'); ?></a>
				</div>
				<?php
			}
		}
		?>
		<div style="clear: both"></div>
		<div id="item-buttons" style="text-align: right;">

		<?php do_action( 'bp_group_header_actions' ); ?>

		</div><!-- #item-buttons -->
		<hr/>
		
		<?php
		if( have_rows('lieux_de_tournage', $fiche_projet_post_id) ){
			echo '<h2>Tournage</h2>';
			echo '<div class="acf-map">';
			while ( have_rows('lieux_de_tournage', $fiche_projet_post_id) )  {
				the_row();
				if($location = get_sub_field('adresse', $fiche_projet_post_id)){
					echo '<div class="marker" data-lat="'. $location['lat'] .'" data-lng="'. $location['lng'] .'">';
					the_sub_field('adresse', $fiche_projet_post_id);
					echo '<br/>';
					the_sub_field('jours', $fiche_projet_post_id); 
					echo ' de ';
					the_sub_field('tournage_debut', $fiche_projet_post_id);
					echo ' à ';
					the_sub_field('tournage_fin', $fiche_projet_post_id);
					echo '</div>';
				}
			}
			echo '</div>';
		}
		?>
		<?php //the_field('lieux_de_tournage', $fiche_projet_post_id); ?>
		
			<?php
		if( have_rows('lieux_de_tournage', $fiche_projet_post_id) ){
			echo '<h3>Calendrier du tournage</h3>
			<div class="red">';
			while ( have_rows('lieux_de_tournage', $fiche_projet_post_id) )  {
				the_row();
				echo '<div class="boxcal" style="height: 120px;">';
				echo '<div class="day">
				'. preg_replace('`[^0-9]`', '', get_sub_field('jours', $fiche_projet_post_id)) .'
				</div>';
				echo '<div class="strong">'. get_sub_field('jours', $fiche_projet_post_id) .', de ';
				the_sub_field('tournage_debut', $fiche_projet_post_id);
				echo ' à ';
				the_sub_field('tournage_fin', $fiche_projet_post_id);
				echo '</div>';
				if($location = get_sub_field('adresse', $fiche_projet_post_id)){
					echo '<a href="https://www.google.ch/maps/place/'. get_sub_field('adresse', $fiche_projet_post_id)['address'] .'" target="_blank">'. get_sub_field('adresse', $fiche_projet_post_id)['address'] .'</a>';
				}
				echo '<div class="dispositif">Dispositif: ';
				the_sub_field('tournage_dispositif', $fiche_projet_post_id);
				echo '</div>';
				if(get_sub_field('tournage_autorisation', $fiche_projet_post_id)) {
					echo "Demande d'autorisation";
				}
				echo '<hr/>';
				echo '</div>';
			}
			echo '</div>';
		}
		?>
		
		<div style="clear: both"></div>
		
		<?php
		$projections = array(
		'Session 1 (ciné-concert) 19/01' => '19 janvier à 20h30, Ciné-concert Alhambra',
		'Session 2 (libre) 22/01' => '22 janvier à 21h, salle centrale de la Madeleine',
		'Session 3 (libre) 26/01' => '26 janvier à 21h, salle centrale de la Madeleine',
		);
	
		foreach($sessions as $session){
			//date de projection si définie
			if(isset($projections[$session])) {
				echo '<h1>Projection le '. $projections[$session] .'</h1>';
			}
		}
?>
		<h3>Photos de tournage</h3>
		<?php
		$images = get_field('medias', $fiche_projet_post_id);

		if( $images ): ?>
			<?php foreach( $images as $image ): ?>
				<div style="float: left; margin-right: 10px; margin-bottom: 10px;">
					<a href="<?php echo $image['url']; ?>">
						 <img src="<?php echo $image['sizes']['thumbnail']; ?>" alt="<?php echo $image['alt']; ?>" />
					</a>
					<br/><?php echo $image['caption']; ?>
				</div>
			<?php endforeach; ?>

		<?php endif; ?>
		<div style="clear: both"></div>
		<?php
		//images des membres
		echo do_shortcode( '[gallery size="thumbnail" link="file" columns="8" id="'. $fiche_projet_post_id .'"]' );
		?>
		<div style="clear: both"></div>
		
	</div>
	<div class="col-sm-4 besoins projet">

		<h3 class="red">Besoins</h3>
		
		<?php
		//link to group forum
		$link_2_forum = '<div class="forumlink"><a href="'. bp_get_group_forum_permalink() .'">SUGGÉRER | POSTULER / FOURNIR</a></div>';
		?>
		
		<?php
		if(get_field('besoin_equipe', $fiche_projet_post_id)){
			echo '<h4>Équipe</h4>
			<div><ul>';
			foreach(get_field('besoin_equipe', $fiche_projet_post_id) as $need){
				echo '<li>'. $need .'</li>';
			}
			echo '</ul>'. $link_2_forum .'
			</div>';
		}
		?>

		<?php
		if( have_rows('besoin_comediens', $fiche_projet_post_id) ){
			echo '<h4>Casting</h4>
			<div><ul>';
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
			echo '</ul>'. $link_2_forum .'</div>';
		}
		?>

		<?php
		if(get_field('besoin_figurants', $fiche_projet_post_id)) {
			echo "<h4>Figurant-e-s</h4><div>";
			the_field('besoin_figurants', $fiche_projet_post_id);
			echo '<div style="clear: both"></div>'. $link_2_forum .'</div>';
		}
		
		?>

		<?php
		if(get_field('casting_et_direction_acteur', $fiche_projet_post_id)) {
			echo "<h4>Casting et direction d'acteur</h4><div>$link_2_forum</div>";
		}
		?>
		
		<?php 
		if(get_field('besoin_lieux_de_tournage', $fiche_projet_post_id)){
			echo '<h4>Lieux de tournage</h4><div>';
			the_field('besoin_lieux_de_tournage', $fiche_projet_post_id);
			$images = get_field('besoin_lieux_de_tournage_photos', $fiche_projet_post_id);

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
			echo '<div style="clear: both"></div>'. $link_2_forum .'</div>';
		}
		?>
		 
		 <?php 
		if(get_field('besoin_accessoires', $fiche_projet_post_id)){
			echo '<h4>Accessoires</h4><div>';
			the_field('besoin_accessoires', $fiche_projet_post_id);
			$images = get_field('besoin_accessoires_photos', $fiche_projet_post_id);

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
			echo '<div style="clear: both"></div>'. $link_2_forum .'</div>';
		}
		 ?>
		
		 <?php 
		if(get_field('besoin_costumes', $fiche_projet_post_id)){
			echo '<h4>Costumes</h4><div>';
			the_field('besoin_costumes', $fiche_projet_post_id);
			$images = get_field('besoin_costumes_photos', $fiche_projet_post_id);

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
			echo '<div style="clear: both"></div>'. $link_2_forum .'</div>';
		}
		 ?>

		
			<?php
			//besoin_maquillage
			if( have_rows('besoin_maquillage_oui', $fiche_projet_post_id) ) {
				echo '<h4>Maquillage</h4>
				<div><ul>';
				while ( have_rows('besoin_maquillage_oui', $fiche_projet_post_id) ) {
					the_row();
					echo '<li>'. get_sub_field('nombre', $fiche_projet_post_id) .' comédien(s) | '. get_sub_field('type', $fiche_projet_post_id) .' | PAT '. get_sub_field('pat', $fiche_projet_post_id) .'</li>';
				}
				echo '</ul>'. $link_2_forum .'</div>';
			}
			?>
	
			<?php
			if( have_rows('besoin_coiffure_oui', $fiche_projet_post_id) ) {
				echo '<h4>Coiffure</h4>
				<div><ul>';
				while ( have_rows('besoin_coiffure_oui', $fiche_projet_post_id) ) {
					the_row();
					echo '<li>'. get_sub_field('nombre', $fiche_projet_post_id) .' comédien(s) | '. get_sub_field('type', $fiche_projet_post_id) .' | PAT '. get_sub_field('pat', $fiche_projet_post_id) .'</li>';
				}
				echo '</ul>'. $link_2_forum .'</div>';
			}
			?>
			
			<?php
			//fil d'activité déplacé de home
			// Load appropriate front template
			bp_groups_front_template_part(); 
			?>
	</div>

</div>
