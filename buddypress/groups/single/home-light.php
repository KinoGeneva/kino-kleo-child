<?php

//meta info, id du groupe et id de l'article associé
$group_id = bp_get_group_id(); 
$fiche_projet_post_id = groups_get_groupmeta($group_id, 'fiche-projet-post-id');

?>

<div class="row">
	<div class="col-sm-12 projet">

		<h1><?php bp_group_name(); ?></h1>
		<?php
		if($fiche_projet_post_id){
			echo 'Un film de <span class="strong">';
			$id_real = get_field('realisateur', $fiche_projet_post_id)['ID'];
			echo bp_core_get_userlink($id_real) .' | ';
			the_field('duree', $fiche_projet_post_id); ?> | <?php the_field('genre', $fiche_projet_post_id); 
			echo '</span>';
		}

		$sessions = wp_get_object_terms( $group_id , 'bp_group_tags', array('fields' => 'names') );
		
		echo '<h3 class="red">';
		foreach($sessions as $session){
			echo $session .' ';
		}
		echo '</h3>';
		?>
		
		<div style="clear: both;"></div>
		
		<hr/>
		<h3>Synopsis</h3>
		<?php bp_group_description() ?>

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
				echo '<h3>Projection le '. $projections[$session] .'</h3>';
			}
		}
		?>
	</div>

</div>
