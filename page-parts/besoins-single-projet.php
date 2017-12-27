<?php
/* affichage d'un projet */
	global $portfolio_id;
	global $include_portfolio_ids;
		
	$group_id = array_search( $portfolio_id , $include_portfolio_ids );
	$group = groups_get_group( array( 'group_id' => $group_id ) );
?>
<li <?php bp_group_class(); ?>>
	<div class="group-inner-list animated animate-when-almost-visible bottom-to-top">
		  
		<?php
			//afficher l'avatar du réal ou sinon du projet
			$id_real = get_field('realisateur', $portfolio_id)['ID'];
			if(!empty($id_real)){
				$avatar = get_avatar($id_real,80);
			}
			else {
				$avatar = bp_get_group_avatar( $group_id,'type=full&width=80&height=80' );
			}
			?>
		<div class="item-avatar rounded">
			<a href="<?php bp_group_permalink($group); ?>"><?php echo $avatar ?></a>
			<span class="member-count"><?php echo(groups_get_groupmeta( $group_id, 'total_member_count')); ?></span>
		</div>

		<?php
		$display_real = '';
		if(!empty($id_real)){
			$display_real = ', de '. bp_core_get_user_displayname($id_real);
		}
		?>

		<div class="item">
			<div class="item-title"><a href="<?php bp_group_permalink($group); ?>"><?php bp_group_name($group); ?></a><?php echo  $display_real ?></div>
			
			<div class="item-desc"><?php bp_group_description_excerpt($group); ?></div>


		<div class="besoins-projets"><h4>Besoins pour le tournage:</h4>
			<?php
			/*
			$project_values = array();
			$project_values = get_post_meta( $post->ID );
			* */

			if($besoins_equipe = get_field('besoin_equipe', $portfolio_id)){
				echo '<b>Équipe</b>
				<div><ul>';
				foreach($besoins_equipe as $besoin_equipe){
					echo '<li>'. $besoin_equipe .'</li>';
				}
				echo '</ul>
				</div>';
			}
			?>

			<?php
			if( have_rows('besoin_comediens', $portfolio_id) ){
				echo '<b>Casting</b>
				<div><ul>';
				$c=0;
				while ( have_rows('besoin_comediens', $portfolio_id) )  {
					$c++;
					the_row();
					$casting = array();
					if($besoin_comedien_sexe = get_sub_field('besoin_comedien_sexe', $portfolio_id) ){
						if($besoin_comedien_sexe != 'Indifférent'){
							$casting[] = get_sub_field('besoin_comedien_sexe', $portfolio_id);
						}
					}
					$besoin_comedien_age_minimum = get_sub_field('besoin_comedien_age_minimum', $portfolio_id);
					$besoin_comedien_age_maximum = get_sub_field('besoin_comedien_age_maximum', $portfolio_id);
					if($besoin_comedien_age_minimum && $besoin_comedien_age_maximum) {
						$casting[] = 'âge caméra de '. $besoin_comedien_age_minimum .' à '. $besoin_comedien_age_maximum .' ans';
					}
										
					if($besoin_comedien_cheveux = get_sub_field('besoin_comedien_cheveux', $portfolio_id)){
						if($besoin_comedien_cheveux != 'Indifférent'){
							$casting[] = 'cheveux '. $besoin_comedien_cheveux;
						}
					}
					
					if($besoin_comedien_yeux = get_sub_field('besoin_comedien_yeux', $portfolio_id)){
						if($besoin_comedien_yeux != 'Indifférent'){
							$casting[] = 'yeux '. $besoin_comedien_yeux;
						}
					}
					
					if($besoin_comedien_langues_jouees = get_sub_field('besoin_comedien_langues_jouees', $portfolio_id)){
						$casting[] = 'parlant '. implode(' + ', $besoin_comedien_langues_jouees);
					}
					
					if($besoin_comedien_talents = get_sub_field('besoin_comedien_talents', $portfolio_id)){
						$casting[] = implode(', ', $besoin_comedien_talents);
					}
					
					//display casting
					echo '<li>comédien '. $c .' : ';
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
			if(get_field('casting_et_direction_acteur', $portfolio_id)) {
				echo "<b>Casting et direction d'acteur</b>";
			}
			?>
			
			<?php 
			if(get_field('besoin_lieux_de_tournage', $portfolio_id)){
				echo '<b>Lieux de tournage</b><div>';
				the_field('besoin_lieux_de_tournage', $portfolio_id);
				$images = get_field('besoin_lieux_de_tournage_photos', $portfolio_id);

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
			if(get_field('besoin_accessoires', $portfolio_id)){
				echo '<b>Accessoires</b><div>';
				the_field('besoin_accessoires', $portfolio_id);
				echo '</div>';
			}
			 ?>
			
			 <?php 
			if(get_field('besoin_costumes', $portfolio_id)){
				echo '<b>Costumes</b><div>';
				the_field('besoin_costumes', $portfolio_id);
				echo '</div>';
			}
			 ?>

			
				<?php
				//besoin_maquillage
				if(get_field('besoin_maquillage', $portfolio_id)) {
					echo '<b>Maquillage</b>
					<div><ul>';
					if(get_field('maquillage_1', $portfolio_id)) {
						echo '<li>'. get_field('maquillage_1', $portfolio_id) .' comédien(s) | naturel | PAT '. get_field('jour_et_horaire_pat_1', $portfolio_id) .'</li>';
					}
					if(get_field('maquillage_2')) {
						echo '<li>'. get_field('maquillage_2', $portfolio_id) . ' comédien(s) | soutenu | PAT '. get_field('jour_et_horaire_pat_2', $portfolio_id) .'</li>';
					}
					if(get_field('maquillage_3')) {
						echo '<li>'. get_field('maquillage_3', $portfolio_id) .' comédien(s) | vieillir | PAT '. get_field('jour_et_horaire_pat_3', $portfolio_id) .'</li>';
					}
					if(get_field('maquillage_4')) {
						echo '<li>'. get_field('maquillage_4', $portfolio_id) .' comédien(s) | FX | PAT '. get_field('jour_et_horaire_pat_4', $portfolio_id) .'</li>';
					}
					echo '</ul></div>';
				}
				?>
		
				<?php
				if(get_field('besoin_coiffure', $portfolio_id)) {
					echo '<b>Coiffure</b>';
					echo '<div>'. get_field('coiffure_nombre_de_comedien', $portfolio_id) .' comédien(s) | coiffure type '. get_field('type_de_coiffure', $portfolio_id) .' | PAT: '. get_field('coiffure_jour_et_horaire_pat', $portfolio_id) .'<br/></div>';
				}
				?>
		</div><?php //fin de l'affichage des besoins ?>
		
	</div>
				
</li>
