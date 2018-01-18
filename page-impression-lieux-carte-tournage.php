<?php
/**
 * Un template pour imprimer les lieux de tournage des projets par date
 */
?>
<?php
$homeurl = home_url();
$url = get_stylesheet_directory_uri(); ?>

<!doctype html>
<html class="no-js" lang="" moznomarginboxes mozdisallowselectionprint>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="x-ua-compatible" content="ie=edge">
        <title>Lieux de tournage</title>
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width, initial-scale=1">
		<meta name="robots" content="noindex, nofollow">
		
		<link href="https://fonts.googleapis.com/css?family=Raleway" rel="stylesheet"> 
		<link rel="stylesheet" href="<?php echo $url ?>/css/dev/31-print.css">
		
		<script type='text/javascript' src='<?php echo $homeurl; ?>/wp-includes/js/jquery/ui/core.min.js?ver=1.11.4'></script>
		<script type='text/javascript' src='<?php echo $homeurl; ?>/wp-includes/js/jquery/ui/widget.min.js?ver=1.11.4'></script>
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
		<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDJ-H97yAa8Ze_h_Vu9Vd05of-i__ozsA4"></script>
		<script src="<?php echo $url ;?>/js/map.js"></script>
    </head>
    <body>
    <?php
    	//current url
    	$current_url = home_url(add_query_arg(array(),$wp->request));
    	$css_url = get_stylesheet_directory_uri();

    	if ( current_user_can( "read") ) {
    	
    ?>
		<!-- Begin Article -->
		<article>
			<div class="article-content">
				
			<?php


			//init du tableau des besoins par date
			$group_2_display = array(
				
				//dates 2018
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
			
//recherche des groupes
			if ( bp_has_groups('per_page=50,status=private') ) {

				while ( bp_groups() ) {
					bp_the_group();

					//meta info, id du groupe et id de l'article associé
					$group_id = bp_get_group_id(); 
					$fiche_projet_post_id = groups_get_groupmeta($group_id, 'fiche-projet-post-id');
					$id_real = get_field('realisateur', $fiche_projet_post_id)['ID'];
									
					$sessions = wp_get_object_terms( $group_id , 'bp_group_tags', array('fields' => 'names') );
					
					//assistant réal?
					$ass_real = '';
					$ass_real_id = '';
					$ass_real_nom = '';
					$ass_real_email = '';
					$ass_real_tel = '';
					if( have_rows('equipe', $fiche_projet_post_id) ){
						while ( have_rows('equipe', $fiche_projet_post_id) ) {
							the_row();
							if($role = get_sub_field('role', $fiche_projet_post_id) ){
								if($role=='Assistant-e à la réalisation'){
									if($ass_real = get_sub_field('membre_kino_kabaret_2017', $fiche_projet_post_id)){
										$ass_real_id = $ass_real['ID'];
										$ass_real_nom = bp_core_get_user_displayname($ass_real_id);
										$ass_real_email = xprofile_get_field_data('e-mail', $ass_real_id);
										$ass_real_tel = xprofile_get_field_data('Téléphone', $ass_real_id);
									}
								}
							}
						}
					}
					
					//test sur les lieus de tournage définis
					if( have_rows('lieux_de_tournage', $fiche_projet_post_id) ) {
						while ( have_rows('lieux_de_tournage', $fiche_projet_post_id) ) {
							the_row();
							$date_tournage = get_sub_field('jours', $fiche_projet_post_id);
							$heure_debut =  get_sub_field('tournage_debut', $fiche_projet_post_id);
							$heure_fin = get_sub_field('tournage_fin', $fiche_projet_post_id);
							if($location = get_sub_field('adresse', $fiche_projet_post_id)){
								$adresse_tournage = $location['address'];
								$adresse_lat = $location['lat'];
								$adresse_lng = $location['lng'];
							}
							else {
								$adresse_tournage = '';
								$adresse_lat = 0;
								$adresse_lng = 0;
							}

							//tableau des données
							if(isset($group_2_display[$date_tournage])){
								$group_2_display[$date_tournage][] = array(								
									'heure_debut' => $heure_debut,
									'heure_fin' => $heure_fin,
									'adresse_tournage' => $adresse_tournage,
									'adresse_lat' => $adresse_lat,
									'adresse_lng' => $adresse_lng,
									'group_id' => $group_id,
									'fiche_projet_post_id' => $fiche_projet_post_id,
									'id_real' => $id_real,
									'nom_real' => bp_core_get_user_displayname($id_real),
									'email_real' => xprofile_get_field_data('e-mail', $id_real),
									'tel_real' => xprofile_get_field_data('Téléphone', $id_real),
									'sessions' => $sessions,
									'group_name' => bp_get_group_name(),
									'ass_real_id' => $ass_real_id,
									'ass_real_nom' => $ass_real_nom,
									'ass_real_email' => $ass_real_email,
									'ass_real_tel' => $ass_real_tel
								);
							}
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
				<span class="bg bggreen">Lieux</span>
				<br/>
				<span class="bg bgblack" style="margin-left: 47px;">de tournage</span>
			</div>
		<?php
		
		foreach($group_2_display as $date => $entry){
			if(!empty($entry)){ ?>
				<div class="profile clearfix print-profile">
					<div class="bandeau no-screen">
						<span class="bg bggreen">Lieux</span>
						<br/>
						<span class="bg bgblack" style="margin-left: 47px;">de tournage</span>
					</div>
			<?php
							
				echo '<h1 class="green">'. $date .'</h1>';
				uasort($entry, function($a, $b) {
				   if ($a == $b) {
					return 0;
					}
				return ($a < $b) ? -1 : 1;
				});

				//carte google map
		
				echo '<div class="acf-map">';
				foreach($entry as $projet){
					if($projet['adresse_lat']!=0 &&  $projet['adresse_lng']!= 0){
						echo '<div class="marker" data-lat="'. $projet['adresse_lat'] .'" data-lng="'. $projet['adresse_lng'] .'">';
						echo $projet['adresse_tournage'];
						echo '<br/>';
						echo $date;
						echo ' de ';
						echo $projet['heure_debut'];
						echo ' à ';
						echo $projet['heure_fin'];
						echo '</div>';
					}
				}

				echo '</div>';

				?>
		
				<table>
				<tr>
					<th style="width: 40mm">Horaires</th>
					<th style="width: 80mm">Adresse</th>
					<th style="width: 80mm">Film</th>
				</tr>
				
				<?php
				
				foreach($entry as $projet){ ?>
					<tr style="page-break-inside: avoid;">
						
				<?php
					
					//PAT

					echo '<td>'. $projet['heure_debut'] .' - '. $projet['heure_fin'] .'</td>';
					
					//Détails
					echo '<td>'. $projet['adresse_tournage'];
					//google map
					
					echo '</td>';

					//Film
					?>
					<td>
						<div class="strong"><?php echo $projet['group_name']; ?></div>
						<div><?php echo $projet['nom_real']; ?></div>
						<div><?php echo $projet['email_real'] ?></div>
						<div><?php echo $projet['tel_real'] ?></div>
						<?php
						if(!empty($projet['ass_real_id'])){
							echo '<div style="font-size: 85%;">Assistant-e réal :'. $projet['ass_real_nom'] .' | '. $projet['ass_real_email'] .' | '. $projet['ass_real_tel'] .'</div>';
						}
						?>
						<div style="font-size: 85%;"><?php foreach($projet['sessions'] as $session) { echo $session .'<br/>'; } ?></div>
					</td>
					
					<td>
					<?php
					//Lieu
					
					
					?>
					</td>


				</tr>
				<?php
				}  // end foreach $entry ?>
				</table>
					<div class="footer no-screen">
						&nbsp;
					</div>
				</div>
		<?php // fin de profile 
			}
		}
		?>

				
			</div><!--end article-content-->

		</article>
		<!-- End  Article -->
<?php } else {

echo '<h1>Désolé, l’accès à cette page est réservé aux membres Kino Geneva!</h1>';
}
// end if current user can...  ?>  
    </body>
</html>
