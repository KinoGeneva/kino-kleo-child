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
    <?php
    	
    	if ( current_user_can( "read") ) {
    	
    ?>
		<!-- Begin Article -->
		<article>
			<div class="article-content">
			
			<?php
			if ( bp_has_groups('per_page=50') ) {
				//tri du tableau par date...
				$group_2_display = array(
				'dimanche 14 janvier' => array(),
				'lundi 15 janvier' => array(),
				'mardi 16 janvier' => array(),
				'mercredi 17 janvier' => array(),
				'jeudi 18 janvier' => array(),
				'vendredi 19 janvier' => array(),
				'samedi 20 janvier' => array(),
				'dimanche 21 janvier' => array(),
				'lundi 22 janvier' => array(),
				'mardi 23 janvier' => array(),
				'mercredi 24 janvier' => array(),
				'jeudi 25 janvier' => array(),
				'vendredi 26 janvier' => array(),
				);
				
				$group_2_display = array(
				'sans date' => array(),
				'mardi 10 janvier' => array(),
				'mercredi 11 janvier' => array(),
				'vendredi 13 janvier' => array(),
				'samedi 14 janvier' => array(),
				'lundi 16 janvier' => array(),
				'mardi 17 janvier' => array(),
				
				);
				
				while ( bp_groups() ) {
					bp_the_group();

					//meta info, id du groupe et id de l'article associé
					$group_id = bp_get_group_id(); 
					$fiche_projet_post_id = groups_get_groupmeta($group_id, 'fiche-projet-post-id');
					$id_real = get_field('realisateur', $fiche_projet_post_id)['ID'];
					
					//obtenir la session automatiquement
					$sessions_terms = get_terms( array(
						'taxonomy' => 'user-group',
						'name__like' => 'session' ,
						'fields' => 'names',
					) );
					$user_terms = wp_get_object_terms( $id_real , 'user-group', array('fields' => 'names'));

					$sessions = current( ( array_intersect( $sessions_terms,$user_terms ) ) );
					
					//test si besoin en accessoire ou en costume
					if( get_field('besoin_accessoires', $fiche_projet_post_id) || get_field('besoin_costumes', $fiche_projet_post_id) ){

						//classement par jour de tournage
						if( have_rows('lieux_de_tournage', $fiche_projet_post_id) ) {
							while ( have_rows('lieux_de_tournage', $fiche_projet_post_id) ) {
								the_row();
								$date = get_sub_field('jours', $fiche_projet_post_id);
								$horaire = 'de '. get_sub_field('tournage_debut', $fiche_projet_post_id) .' à '. get_sub_field('tournage_fin', $fiche_projet_post_id);
								$adresse = get_sub_field('adresse', $fiche_projet_post_id)['address'];
								
								$group_2_display[$date][] = array(
									'date' => $date,
									'horaire' => $horaire,
									'adresse' => $adresse,
									'group_id' => $group_id,
									'fiche_projet_post_id' => $fiche_projet_post_id,
									'id_real' => $id_real,
									'nom_real' => bp_core_get_user_displayname($id_real),
									'email_real' => xprofile_get_field_data('e-mail', $id_real),
									'tel_real' => xprofile_get_field_data('Téléphone', $id_real),
									'sessions' => $sessions,
									'group_name' => bp_get_group_name(),
								);
							}
						}
						//sans date de tournage définie
						else {
							$group_2_display['sans date'][] = array(
								'date' => '',
								'horaire' => '',
								'group_id' => $group_id,
								'fiche_projet_post_id' => $fiche_projet_post_id,
								'id_real' => $id_real,
								'nom_real' => bp_core_get_user_displayname($id_real),
								'email_real' => xprofile_get_field_data('e-mail', $id_real),
								'tel_real' => xprofile_get_field_data('Téléphone', $id_real),
								'sessions' => $sessions,
								'group_name' => bp_get_group_name(),
							);
						}
					}
				}
			}
			/*
			echo '<pre>';
			print_r($group_2_display);
			echo '</pre>';
*/

			?>
			
			
			<?php
			foreach($group_2_display as $date => $entry){
				echo '<div class="profile clearfix print-profile">';
				echo '<h2>'. $date .'</h2>';
				uasort($entry, function($a, $b) {
				   if ($a == $b) {
					return 0;
					}
				return ($a < $b) ? -1 : 1;
				});
				foreach($entry as $projet){
					echo '<div style="page-break-inside: avoid;">';
					if($date != 'sans date'){
						echo '<h3>'. $projet['horaire'] .'<br/>'.  $projet['adresse'] .'</h3>';
					}
					?>			
					<div>Tournage de <b><?php echo $projet['group_name']; ?></b> (<?php echo $projet['sessions']; ?>)</div>
					<div>Un film de <b><?php echo $projet['nom_real']; ?></b> | <?php echo $projet['email_real'] ?> | <?php echo $projet['tel_real'] ?></div>

		<div class="besoins">
		

		 <?php 
		if(get_field('besoin_accessoires', $projet['fiche_projet_post_id'])){
			echo '<div><h4 class="red">Besoins en accessoires</h4>';
			the_field('besoin_accessoires', $projet['fiche_projet_post_id']);
			$images = get_field('besoin_accessoires_photos', $projet['fiche_projet_post_id']);

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
			echo '</div>';
		}
		 ?>
		
		 <?php 
		if(get_field('besoin_costumes', $projet['fiche_projet_post_id'])){
			echo '<div><h4 class="red">Besoins en costumes</h4>';
			the_field('besoin_costumes', $projet['fiche_projet_post_id']);
			$images = get_field('besoin_costumes_photos', $projet['fiche_projet_post_id']);

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
			echo '</div>';
		}
		 ?>
		</div>
		<hr/>
			</div>
			<div style="clear: both;"></div>
			<?php } ?>
			</div>
			<div class="page-break"></div>
		<?php }?>
			
			</div><!--end article-content-->

		</article>
		<!-- End  Article -->
<?php } else {

echo '<h1>Désolé, l’accès à cette page est réservé aux membres Kino Geneva!</h1>';
}
// end if current user can...  ?>  
    </body>
</html>
