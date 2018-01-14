<?php
/**
 * Un template pour imprimer les lieux de tournage des projets par date
 */
?>
<?php $url = get_stylesheet_directory_uri(); ?>

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
					
					//test sur les lieus de tournage définis
					if( have_rows('lieux_de_tournage', $fiche_projet_post_id) ) {
						while ( have_rows('lieux_de_tournage', $fiche_projet_post_id) ) {
							the_row();
							$date_tournage = get_sub_field('jours', $fiche_projet_post_id);
							$heure_debut =  get_sub_field('tournage_debut', $fiche_projet_post_id);
							$heure_fin = get_sub_field('tournage_fin', $fiche_projet_post_id);
							if(get_sub_field('adresse', $fiche_projet_post_id)){
								$adresse_tournage = get_sub_field('adresse', $fiche_projet_post_id)['address'];
							}
							else {
								$adresse_tournage = '';
							}
							//tableau des données
							if(isset($group_2_display[$date_tournage])){
								$group_2_display[$date_tournage][] = array(								
									'heure_debut' => $heure_debut,
									'heure_fin' => $heure_fin,
									'adresse_tournage' => $adresse_tournage,
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
					echo '<td>'. $projet['adresse_tournage'] .'</td>';
					
					//Film
					?>
					<td>
						<div class="strong"><?php echo $projet['group_name']; ?></div>
						<div><?php echo $projet['nom_real']; ?></div>
						<div><?php echo $projet['email_real'] ?></div>
						<div><?php echo $projet['tel_real'] ?></div>
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
