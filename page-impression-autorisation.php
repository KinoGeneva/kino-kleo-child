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
		<link rel="stylesheet" href="<?php echo $url ?>/css/dev/31-print.css">

    </head>
    <body>
    <?php
    	
		$kino_fields = kino_test_fields();

    	if ( current_user_can( "publish_pages") ) {
    	
    ?>
		<!-- Begin Article -->
		<article>
			<div class="article-content">
			<?php

			//recherche des groupes
			$args = array(
				'status' => array('public','private'),
				'per_page' => 120
			);
			$projets = groups_get_groups( $args );
			
			foreach($projets['groups'] as $project){

				//meta info, id du groupe et id de l'article associé
				$group_id = $project->id;
				$fiche_projet_post_id = groups_get_groupmeta($group_id, 'fiche-projet-post-id');
				$id_real = get_field('realisateur', $fiche_projet_post_id)['ID'];

				//adresse réal
				$adresse_real = xprofile_get_field_data( $kino_fields["rue"], $id_real ). '<br/>'. 
				xprofile_get_field_data( $kino_fields["ville"], $id_real ). '<br/>'. 
				xprofile_get_field_data( $kino_fields["code-postal"], $id_real ) .' '. xprofile_get_field_data( $kino_fields["pays"], $id_real );
				
				
				//stockage des données pour les afficher
				$group_2_display[$group_id] = array(
					'group_id' => $group_id,
					'fiche_projet_post_id' => $fiche_projet_post_id,
					'id_real' => $id_real,
					'nom_real' => bp_core_get_user_displayname($id_real),
					'email_real' => xprofile_get_field_data('e-mail', $id_real),
					'tel_real' => xprofile_get_field_data('Téléphone', $id_real),
					'adresse_real' => $adresse_real,
					'sessions' => wp_get_object_terms( $group_id , 'bp_group_tags', array('fields' => 'names') ),
					'group_name' => $project->name,
					'synopsis' => $project->description,
					'autorisation' => 0,
					'tournages' => array()
				);
				
				//ajout des jours de tournage
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
							if(!$dispositif = get_sub_field('tournage_dispositif', $fiche_projet_post_id)){
								$dispositif = array();
							}
							if(!$scene = get_sub_field('tournage_scene', $fiche_projet_post_id)){
								$scene = '';
							}
							
							//info autorisation = OUI
							$group_2_display[$group_id]['autorisation'] = 1;
							
							//stockage des jours de tournage
							$group_2_display[$group_id]['tournages'][] = array(
								'date' => $date,
								'horaire' => $horaire,
								'adresse' => $adresse,
								'dispositif' => $dispositif,
								'scene' => $scene
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
		<div class="bandeau no-print">
				<span class="bg bggreen">Autorisations</span>
				<br/>
				<span class="bg bgblack" style="margin-left: 47px;">de tournages</span>
		</div>
<?php
		foreach($group_2_display as $group_id => $projet){
			if(!empty($projet) && $projet['autorisation'] == 1){
				?>
				<div class="profile clearfix print-profile">
					<div class="bandeau no-screen">
						<span class="bg bggreen">Demande d'autorisation</span>
						<br/>
						<span class="bg bgblack" style="margin-left: 47px;">de tournages</span>
						
					</div>
					<img src="https://kinogeneva.ch/wp-content/uploads/2015/11/KinoGeneva_Red_S.jpg" alt="kinoGeneva" class="no-screen right"/>
					<div>
				<?php
				
				echo '<h1 class="green">'. $projet['group_name'] .'</h1>';
				?>
					</div>
					
				<?php
				//réal
					
				echo '<div>De <span class="strong">'. $projet['nom_real'] .'</span> | ';
				echo $projet['tel_real'] .' | ';
				echo $projet['email_real'] .'<br/>';
				echo $projet['adresse_real'];
				echo '</div>';
	
				echo '<p><span class="strong">Nombre de personnes</span> sur le plateau: ';
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
					echo '</p>';
				
				echo '<div><div class="strong">Synopsis:</div>'. $projet['synopsis'] .'</div>';
				
				//dates
				if(!empty($projet['tournages'])){
					echo '<div><h1 class="red">Tournages</h1>';
					foreach($projet['tournages'] as $i => $tournage){
						echo '<div style="page-break-inside: avoid;">';
						//horaire et lieux
						echo '<div class="strong red">' . ($i+1) .'. ';
						echo $tournage['date'] .', ';
						echo $tournage['horaire'];
						echo '</div><div class="strong">';
						echo $tournage['adresse'];
						echo '</div>';
						
						//film

						echo '<p>';
						if(!empty($tournage['dispositif'])){
							echo '<div class="strong">Dispositif de tournage:</div>';
							foreach( $tournage['dispositif'] as $dispositif) {
								echo $dispositif;
							}
							
						}
						echo '</p>';
						
						echo '<p>';
						if(!empty($tournage['scene'])){
							echo '<div class="strong">Description de la scène:</div>';
							echo $tournage['scene'];
						}
						echo '</p>';
						echo '</div>';					
					}
					echo '</div>';
				}

			}  // end !empty($projet) ?>

					<div class="footer no-screen">
						&nbsp;
					</div>
				</div>
		<?php // fin de profile 
		}
		?>

				
			</div><!--end article-content-->
		</article>
		<!-- End  Article -->
<?php } else {

echo '<h1>Désolé, l’accès à cette page est réservé aux administrateurs-trices de Kino Geneva!</h1>';
}
// end if current user can...  ?>  
    </body>
</html>
