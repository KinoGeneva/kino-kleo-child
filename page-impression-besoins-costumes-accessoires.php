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
		<link rel="stylesheet" href="<?php echo $url ?>/css/dev/31-print.css">

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

						//jour de tournage
						if( have_rows('lieux_de_tournage', $fiche_projet_post_id) ) {
							$dates_tournage = array();
							$heures_tournage = array();
							$adresses_tournage = array();
							$l=0;
							while ( have_rows('lieux_de_tournage', $fiche_projet_post_id) ) {
								the_row();
								$dates_tournage[$l] = get_sub_field('jours', $fiche_projet_post_id);
								$heures_tournage[$l] = get_sub_field('tournage_debut', $fiche_projet_post_id) .' - '. get_sub_field('tournage_fin', $fiche_projet_post_id);
								
								if(get_sub_field('adresse', $fiche_projet_post_id)){
									$adresses_tournage[$l] = get_sub_field('adresse', $fiche_projet_post_id)['address'];
								}
								else {
									$adresses_tournage[$l] = '';
								}
								$l++;
							}
						}
						else {
							$dates_tournage = array();
							$heures_tournage = array();
							$adresses_tournage = array();
						}
						//tableau des données
						$group_2_display[] = array(
									'dates' => $dates_tournage,
									'horaires' => $heures_tournage,
									'adresses' => $adresses_tournage,
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
			/*
			echo '<pre>';
			print_r($group_2_display);
			echo '</pre>';
			*/

			?>
			<div class="bandeau no-print">
				<span class="bg bggreen">Besoins</span>
				<br/>
				<span class="bg bgblack" style="margin-left: 47px;">costumes / accessoires</span>
			</div>
			
			<?php
			foreach($group_2_display as $projet){
				
				?>
				<div class="profile clearfix print-profile">
					<div class="bandeau no-screen">
						<span class="bg bggreen">Besoins</span>
						<br/>
						<span class="bg bgblack" style="margin-left: 47px;">maquillage / coiffure</span>
					</div>
					<div class="right title">
					<?php 
					echo $projet['sessions'];
					?>
					</div>
					<div class="film-info">
						<h1 class="green"><?php echo $projet['group_name'] ?></h1>
						
							<div class="strong"><?php echo $projet['nom_real']; ?></div>
							<div><?php echo $projet['tel_real'] ?></div>
							<div><?php echo $projet['email_real'] ?></div>
							
							<h2>lieux de tournage</h2>
						<?php
						foreach($projet['dates'] as $l => $date){
							echo '<div>'. $date .' '. $projet['horaires'][$l] .' / '.  $projet['adresses'][$l] .'</div>';
						}
						?>
					</div>

					<?php
					$all_images = array();
					?>
					<div class="block-50">
						<?php 
						if(get_field('besoin_costumes', $projet['fiche_projet_post_id'])){
							echo '<h1 class="green">Costumes</h1><hr/>';
							the_field('besoin_costumes', $projet['fiche_projet_post_id']);
							$images = get_field('besoin_costumes_photos', $projet['fiche_projet_post_id']);

							if( $images ) {
								foreach( $images as $image ) {
									echo '<img src="'. $image['sizes']['thumbnail'] .'" alt="'. $image['alt'] .'" class="vignette" /> ';
									
								}
							}
							echo '';
						}
						?>
					</div>
					<div class="block-50 end-fiche">

					<?php 
					if(get_field('besoin_accessoires', $projet['fiche_projet_post_id'])){
						echo '<h1 class="green">Accessoires</h1><hr/>';
						the_field('besoin_accessoires', $projet['fiche_projet_post_id']);
						$images = get_field('besoin_accessoires_photos', $projet['fiche_projet_post_id']);
	
						if( $images ) {
							foreach( $images as $image ) {
								echo '<img src="'. $image['sizes']['thumbnail'] .'" alt="'. $image['alt'] .'" class="vignette" /> ';
							}
	
						}
					}
					?>
					</div>
					
					
			<hr class="no-print"/>
			<div class="footer no-screen">
				&nbsp;
			</div>
			</div>
			
			<?php // end .profile
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
