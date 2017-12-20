<?php
/**
 * Un template pour visualiser les membres au profil incomplet
 */
?>

<?php
$extra_classes = 'clearfix';
if ( get_cfield( 'centered_text' ) == 1 )  {
    $extra_classes .= ' text-center';
}
?>

<!-- Begin Article -->
<article id="post-<?php the_ID(); ?>" <?php post_class($extra_classes); ?>>

    <div class="article-content">
        <?php the_content();

		 $url = home_url();
		 
        //on cherche les membres faisant partie du groupe
        //Participants Kabaret : profil incomplet
        
        $kino_fields = kino_test_fields();
        
        $ids_of_kino_incomplete = get_objects_in_term( 
        	$kino_fields['group-kino-incomplete'] , 
        	'user-group' 
        );
         $ids_of_kino_complete = get_objects_in_term(
        	$kino_fields['group-kino-complete'] , 
        	'user-group' 
        );
         $ids_of_kino_pending = get_objects_in_term(
        	$kino_fields['group-kino-pending'] , 
        	'user-group' 
        );
		$ids_of_kino_pending_only = array_diff($ids_of_kino_pending, $ids_of_kino_incomplete, $ids_of_kino_complete);
		$ids_of_kino_incomplete = array_merge($ids_of_kino_pending_only, $ids_of_kino_incomplete);

        //$ids_of_kino_incomplete = array_diff( $ids_of_kino_incomplete, $ids_of_kino_complete );
        $ids_of_kino_incomplete = array_filter($ids_of_kino_incomplete);
        
        echo '<h3>Participants au Kabaret avec profil incomplet: '.count( $ids_of_kino_incomplete ).'</h3>';

		//champs à tester
		global $group, $field;
		$xprofile_requiered_fields = array();
		if (bp_has_profile ()) {
			while (bp_profile_groups ()) {
				bp_the_profile_group ();
				if($group->name!='Conditions'){
					while (bp_profile_fields ()) {
						bp_the_profile_field ();
						if($field->is_required==1) {
							$xprofile_requiered_fields[$group->name][$field->id] = $field->name;
						}
						elseif($field->id == 1872){
							$xprofile_requiered_fields[$group->name][$field->id] = $field->name;
						}
					}
				}
			}
		}
		/*
		echo '<pre>';
		print_r($xprofile_requiered_fields);
		echo '</pre>';
		*/

        if ( !empty( $ids_of_kino_incomplete ) ) {
			if( !empty( $users_kino_incomplete = get_users( array( 'include' => $ids_of_kino_incomplete ) ) ) ) {
				// Contenu du tableau

				// Init:
				$metronom = 1;

				?>
				<table id="kabaret-incomplet" class="table table-hover table-bordered table-condensed">
					<thead>
						<tr>
							<th>#</th>
							<th>Nom</th>
							<th>Email</th>
							<th>Rôle Kabaret</th>
							<th>Identité</th>
							<th>Comédien</th>
							<th>Technicien</th>
							<th>Réalisateur</th>
							<th>Bénévole</th>
							<th>Kabaret</th>
							<th>Inscription au Kabaret</th>
						</tr>
					</thead>
					<tbody>
				<?php
			
				foreach ( $users_kino_incomplete as $user ) {

					//test sur les champs obigatoires
					
					//d'abord tester quels onglets devraient être remplis en fonction du rôle kab
					$kino_user_role = array();
					if( $kino_particiation_boxes = bp_get_profile_field_data( array(
						'field'   => $kino_fields['role-kabaret'],
						'user_id' => $user->ID
					) ) ) {
						foreach ($kino_particiation_boxes as $key => $value) {
							$value = mb_substr($value, 0, 4);
							$kino_user_role[] = $value;
						}
					}
					else {
						$kino_user_role = array();
					}
					//sélection des champs vides à afficher
					$notification = array();
					foreach($xprofile_requiered_fields as $xprofile_group => $xprofile_values) {

						$notification[$xprofile_group] = array();
						foreach( $xprofile_values as $xfield_id => $xfield_name ) {
							$xvalue = bp_get_profile_field_data( array(
								'field'   => $xfield_id,
								'user_id' => $user->ID
							) );
							if( empty( $xvalue ) ) {					
								switch($xprofile_group) {
									case 'Compétence Comédien':
										if ( in_array( "Comé", $kino_user_role )) {
											$notification[$xprofile_group][$xfield_id] = $xfield_name;
										}
									break;
									case 'Compétence Technicien':
										if ( in_array( "Arti", $kino_user_role )) {
											$notification[$xprofile_group][$xfield_id] = $xfield_name;
										}
									break;
									case 'Compétence Réalisateur':
										if ( in_array( "Réal", $kino_user_role )) {
											$notification[$xprofile_group][$xfield_id] = $xfield_name;
										}
									break;
									case 'Aide bénévole':
										if ( in_array( "Béné", $kino_user_role )) {
											$notification[$xprofile_group][$xfield_id] = $xfield_name;
										}
									break;
									default:
										$notification[$xprofile_group][$xfield_id] = $xfield_name;
								}
							}
						}
					}
					
					?>
					<tr>
						<th><?php echo $metronom++; ?></th>
						<?php 
						
								// $user->ID
								echo '<td><a href="'.$url.'/members/'.$user->user_nicename.'/" target="_blank">'.$user->user_login.'</a> ('.$user->display_name.')</td>';
								
								// Email
								echo '<td><a href="mailto:'. $user->user_email .'?Subject=Kino%20Kabaret" target="_top">'. $user->user_email .'</a>';
								
								//mail préformaté
								$mail_content = 'Bonjour '. $user->user_nicename .',';
								$mail_content.= "\r\n";
								$mail_content.= "\r\n";
								$mail_content.= "Votre inscription au Kino Kabaret est incomplète. Complétez les onglets suivants de votre profil sur la plateforme: ";
								$mail_content.= "\r\n";
								$mail_content.= "\r\n";
								foreach( $notification as $xgroup => $xfields ){
									if( !empty( $xfields ) ){
										$mail_content.= 'Onglet '. $xgroup ."\r\n";
										foreach ($xfields as $xfield_id => $xfield_name) {
											$mail_content.= "- ". $xfield_name ."\r\n";
										}
										
										$mail_content.= "\r\n";
									}
								}
								$mail_content.= "Lien vers votre profil : \r\n";
								$mail_content.= $url.'/members/'.$user->user_nicename ."/profile/edit/ \r\n";
								echo '
								<a class="admin-action pending-other" href="mailto:'. $user->user_email .'?Subject=Vos%20informations%20de%20profil%20pour%20le%20Kino%20Kabaret&body='. rawurlencode($mail_content) .'">Envoyer les infos manquantes</a></td>';
						
								// Rôle Kabaret
								if(empty($kino_user_role)){
									echo '<td class="warning"></td>';
								}
								else {
									echo '<td class="success">';								
										// Réalisateur ?
										if ( in_array( "Réal", $kino_user_role )) {
											echo '<span class="kp-pointlist">Réalisateur-trice</span>';
										}
										// Technicien ?
										if ( in_array( "Arti", $kino_user_role )) {
											echo '<span class="kp-pointlist">Artisan-ne / technicien-ne</span>';
										}
										// Comédien ?
										if ( in_array( "Comé", $kino_user_role )) {
											echo '<span class="kp-pointlist">Comédien-ne</span>';
										}
										// Bénévole ?
										if ( in_array( "Béné", $kino_user_role )) {
											echo '<span class="kp-pointlist">Bénévole</span>';
										}
									echo '</td>';
								}
								//champs incomplets
								foreach( $notification as $xgroup => $xfields){
									if(empty($xfields)) {
										echo '<td class="success">';										
										echo '</td>';
									}
									else {
										echo '<td class="warning">Champs manquants:<br/>';
										foreach ($xfields as $xfield_id => $xfield_name) {
											echo '<span class="kp-pointlist">'. $xfield_name .'</span>';
										}
										echo '</td>';
									}
								}
																
								// Registration date
								echo '<td>'. $user->user_registered .'</td>';
								
								
													
					echo '</tr>';
					
				}
				
				echo '</tbody></table>';

			}
		}
		      
        ?>
        
    </div><!--end article-content-->

    <?php  ?>
</article>
<!-- End  Article -->
