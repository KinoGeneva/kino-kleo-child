<?php
/**
 * Un template pour imprimer les besoins des projets de films
 */
?>
<?php $url = get_stylesheet_directory_uri(); ?>

<!doctype html>
<html class="no-js" lang="" moznomarginboxes mozdisallowselectionprint>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="x-ua-compatible" content="ie=edge">
        <title>Impression des besoins des projets de films</title>
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width, initial-scale=1">
		<meta name="robots" content="noindex, nofollow">
		
		<link href="https://fonts.googleapis.com/css?family=Raleway" rel="stylesheet"> 
		<link rel="stylesheet" href="<?php echo $url ?>/css/dev/31-print.css">

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
				//dates 2017
				'mardi 10 janvier' => array(),
				'mercredi 11 janvier' => array(),
				'vendredi 13 janvier' => array(),
				'samedi 14 janvier' => array(),
				'lundi 16 janvier' => array(),
				'mardi 17 janvier' => array(),	
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
			if ( bp_has_groups('per_page=50') ) {

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

					//test si besoin en maquillage ou la coiffure
					if( get_field('besoin_maquillage', $fiche_projet_post_id) || get_field('besoin_coiffure', $fiche_projet_post_id) ){
						$besoins = array('maquillage'=>'besoin_maquillage_oui','coiffure'=>'besoin_coiffure_oui');
						
						//classement par date PAT pour chaque besoin
						foreach($besoins as $nom => $besoin){
							if( have_rows($besoin, $fiche_projet_post_id) ) {
								while ( have_rows($besoin, $fiche_projet_post_id) ) {
									the_row();
									$date = get_sub_field('pat', $fiche_projet_post_id);
									$date_PAT = substr($date, 0 , -6);
									$heure_PAT = substr($date,-5);
									$type = get_sub_field('type', $fiche_projet_post_id);
									$nb = get_sub_field('nombre', $fiche_projet_post_id);
									
									//test sur les lieus de tournage définis
									if( have_rows('lieux_de_tournage', $fiche_projet_post_id) ) {
										$heures_tournage = array();
										$adresses_tournage = array();
										$l=0;
										while ( have_rows('lieux_de_tournage', $fiche_projet_post_id) ) {
											the_row();
											$date_tournage = get_sub_field('jours', $fiche_projet_post_id);
											
											if($date_PAT == $date_tournage){
												if(get_sub_field('tournage_debut', $fiche_projet_post_id) && get_sub_field('adresse', $fiche_projet_post_id)){
													$heures_tournage[$l] = '(de '. get_sub_field('tournage_debut', $fiche_projet_post_id) .' à '. get_sub_field('tournage_fin', $fiche_projet_post_id) .')';
												}
												else {
													$heures_tournage[$l] = '';
												}
												if(get_sub_field('adresse', $fiche_projet_post_id)){
													$adresses_tournage[$l] = get_sub_field('adresse', $fiche_projet_post_id)['address'];
												}
												else {
													$adresses_tournage[$l] = '';
												}
											}
											
											$l++;
										}
									}
									//tableau des données
									$group_2_display[$date_PAT][] = array(
										'heure_PAT' => $heure_PAT,
										'heures_tournage' => $heures_tournage,
										'adresses_tournage' => $adresses_tournage,
										'group_id' => $group_id,
										'fiche_projet_post_id' => $fiche_projet_post_id,
										'id_real' => $id_real,
										'nom_real' => bp_core_get_user_displayname($id_real),
										'email_real' => xprofile_get_field_data('e-mail', $id_real),
										'tel_real' => xprofile_get_field_data('Téléphone', $id_real),
										'sessions' => $sessions,
										'group_name' => bp_get_group_name(),
										'type' => $nom .': '. $type,
										'nb' => $nb,
									);
								}
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
				<span class="bg bggreen">Besoins</span>
				<br/>
				<span class="bg bgblack" style="margin-left: 47px;">maquillage / coiffure</span>
			</div>
		<?php
		foreach($group_2_display as $date => $entry){
			if(!empty($entry)){ ?>
				<div class="profile clearfix print-profile">
					<div class="bandeau no-screen">
						<span class="bg bggreen">Besoins</span>
						<br/>
						<span class="bg bgblack" style="margin-left: 47px;">maquillage / coiffure</span>
					</div>
			<?php
			
			
			//echo $projet['sessions'];
				
				echo '<h1 class="green">'. $date .'</h1>';
				uasort($entry, function($a, $b) {
				   if ($a == $b) {
					return 0;
					}
				return ($a < $b) ? -1 : 1;
				});
					
				?>
				
				<table>
				<tr>
					<th style="width: 25mm">PAT</th>
					<th style="width: 45mm">Détails</th>
					<th style="width: 65mm">Film</th>
					<th style="width: 65mm">Lieu</th>
				</tr>
				
				<?php
				
				foreach($entry as $projet){ ?>
					<tr style="page-break-inside: avoid;">
						
				<?php
					
					//PAT

					echo '<td><div class="strong">'. $projet['heure_PAT'] .'</div></td>';
					
					//Détails
					echo '<td><div class="strong">'. $projet['nb'] .' comédiens</div>'. $projet['type'] .'</td>';
					
					//Film
					?>
					<td>
						<div class="strong"><?php echo $projet['group_name']; ?></div>
						<div><?php echo $projet['nom_real']; ?></div>
						<div><?php echo $projet['email_real'] ?></div>
						<div><?php echo $projet['tel_real'] ?></div>
						<div><?php echo $projet['sessions'] ?></div>
					</td>
					
					<td>
					<?php
					//Lieu
					
					foreach($projet['heures_tournage'] as $l => $heure_tournage){
						echo $projet['adresses_tournage'][$l];
						echo ' '. $heure_tournage;
						echo '<br>';
					}
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
