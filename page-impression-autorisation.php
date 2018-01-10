<?php
/**
 * Un template pour imprimer les autoridsations
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
    	//current url
    	$current_url = home_url(add_query_arg(array(),$wp->request));
    	
    	if ( current_user_can( "publish_pages") ) {
    	
    ?>
		<!-- Begin Article -->
		<article>
			<div class="article-content">
			<h4>Autorisations de tournage</h4>
			<?php

			//init du tableau des besoins par date
			$group_2_display = array(
				//dates 2018
				'sans date' => array(),
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
				//dates 2017
				'mardi 10 janvier' => array(),
				'mercredi 11 janvier' => array(),
				'vendredi 13 janvier' => array(),
				'samedi 14 janvier' => array(),
				'lundi 16 janvier' => array(),
				'mardi 17 janvier' => array(),
			);
			
			
//recherche des groupes
			if ( bp_has_groups('per_page=50') ) {

				while ( bp_groups() ) {
					bp_the_group();

					//meta info, id du groupe et id de l'article associé
					$group_id = bp_get_group_id(); 
					$fiche_projet_post_id = groups_get_groupmeta($group_id, 'fiche-projet-post-id');
					$id_real = get_field('realisateur', $fiche_projet_post_id)['ID'];
					//$group = groups_get_group( array( 'group_id' => $group_id ) );
					
					//obtenir la session automatiquement
					$sessions_terms = get_terms( array(
						'taxonomy' => 'user-group',
						'name__like' => 'session' ,
						'fields' => 'names',
					) );
					$user_terms = wp_get_object_terms( $id_real , 'user-group', array('fields' => 'names'));

					$sessions = current( ( array_intersect( $sessions_terms,$user_terms ) ) );

					//classement par jour de tournage
					if( have_rows('lieux_de_tournage', $fiche_projet_post_id) ) {
						while ( have_rows('lieux_de_tournage', $fiche_projet_post_id) ) {
							the_row();
							
							//demande d'autorisation cochée
							if(get_sub_field('tournage_autorisation', $fiche_projet_post_id)) {
								$date = get_sub_field('jours', $fiche_projet_post_id);
								$horaire = 'de '. get_sub_field('tournage_debut', $fiche_projet_post_id) .' à '. get_sub_field('tournage_fin', $fiche_projet_post_id);
								if(get_sub_field('adresse', $fiche_projet_post_id)){
									$adresse = get_sub_field('adresse', $fiche_projet_post_id)['address'];
								}
								else {
									$adresse = '';
								}
								$dispositif = get_sub_field('tournage_dispositif', $fiche_projet_post_id);
								
								
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
									'dispositif' => $dispositif,
									'synopsis' => bp_get_group_description()
								);
							}
							

						}
					}
					
				}
				//

			}
			/*
			echo '<pre>';
			print_r($group_2_display);
			echo '</pre>';
*/


		foreach($group_2_display as $date => $entry){
			if(!empty($entry)){
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
					echo '<h3>'. $projet['horaire'] .'<br/>'.  $projet['adresse'] .'</h3>';
					
					?>			
					<ul>
						<li>Tournage de <b><?php echo $projet['group_name']; ?></b> (<?php echo $projet['sessions']; ?>)</li>
						<li>Un film de <?php echo $projet['nom_real']; ?> | <?php echo $projet['email_real'] ?> | <?php echo $projet['tel_real'] ?></li>
						<li>Nombre de personnes sur le plateau: 
						<?php
						//on commence à 1 : le réal
						$nb_personnes = 1;
						//equipe
						while ( have_rows('equipe', $projet['fiche_projet_post_id']) ) {
							the_row();
							$nb_personnes++;
						}
						
						//comédien membre
						if( $comediens = get_field('comedien-nes', $projet['fiche_projet_post_id']) ){
							foreach($comediens as $comedien) {
								$nb_personnes++;
							}
						}
						
						//comédien non membre
						if( $comediens_hors = explode(',', get_field('autres_comediens', $projet['fiche_projet_post_id']) )){
							foreach($comediens_hors as $comedien) {
								$nb_personnes++;
							}
						}
						
						echo $nb_personnes;
						?>
						</li>
						<li>
							Dispositif de tournage: <?php echo $projet['dispositif']; ?>
						</li>
						<li>
							Scénario: <?php echo $projet['synopsis']; ?>
						</li>
					</ul>
		<hr/>
			</div>
			<div style="clear: both;"></div>
			<?php } ?>
			</div>
			<div class="page-break"></div>
		<?php }
		}?>
			
			</div><!--end article-content-->

		</article>
		<!-- End  Article -->
<?php } else {

echo '<h1>Désolé, l’accès à cette page est réservé aux membres Kino Geneva!</h1>';
}
// end if current user can...  ?>  
    </body>
</html>
