<?php
/**
 * Un template pour valider les réalisateurs
 */
?>

<?php
$extra_classes = 'clearfix';
if ( get_cfield( 'centered_text' ) == 1 )  {
    $extra_classes .= ' text-center';
}
?>

<article id="post-<?php the_ID(); ?>" <?php post_class($extra_classes); ?>>

    <div class="article-content">
        <?php the_content(); ?>
        
        <?php 
        
        /*
         * Liste: Réalisateurs Plate-forme Kino : à valider
        ***************
        
        Méthode: 
        
        on affiche les usagers de la liste ['group-real-kabaret-pending']
        
        ****/
 				
 				$kino_debug_mode = 'off';
 				
 				$url = site_url();
        
        $user_fields = array( 
        	'user_login', 
        	'user_nicename', 
        	'display_name',
        	'user_email', 
        	'ID' 
        );
        
        $user_query = new WP_User_Query( array( 
        	'fields' => $user_fields 
        ) );
        
				// Réalisateur-trice en attente : Kabaret 2016
				$kino_pending_real_kab = array();
				
				// Réalisateur-trice en attente : Plateforme
				$kino_pending_real_platform = array();
				
				// Réalisateur-trice validé : Kabaret 2016
				$kino_valid_real_kab = array();
				
				// Réalisateur-trice validé : Plateforme
				$kino_valid_real_platform = array();
				
				//***************************************
				
				$kino_fields = kino_test_fields();
				
				$ids_of_real_kab_pending = get_objects_in_term( 
					$kino_fields['group-real-kabaret-pending'] , 
					'user-group' 
				);
				
				if ( $kino_debug_mode == 'on' ) { 
				
						// START DEBUGGING
						// *******************
						
						// ['group-real-kabaret']
						// test also: real-kabaret-pending = ['group-real-kabaret-pending']
				
						echo '<p>for debugging, show list of IDs in term '.$kino_fields['group-real-kabaret-pending'].':</p>';
						
						echo '<pre>';
						echo 'list of IDs in "group-real-kabaret"';
							var_dump($ids_of_real_kab_pending);
						echo '</pre>';
						
						// END DEBUGGING
						// *******************
				}
								
				// User Loop
				if ( ! empty( $user_query->results ) ) {
					foreach ( $user_query->results as $user ) {
						
						// infos about WP_user object
						
						$kino_userid = $user->ID ;
						
						// $kino_user_facts = kino_user_participation( $user->ID );
						// = Trop lourd pour le faire sur l'ensemble des utilisateurs... environ 5000 queries!
						
						// is Valid Réal for Kabaret 2016?
						// ********************************
						// method = test user-group
						// test if $kino_userid is in $ids_of_real_valid_kab
						
						if ( in_array( $kino_userid, $ids_of_real_kab_pending ) ) {
						    // echo '<p>with in_array(), user '.$user->user_login.' / '.$kino_userid.' is <b>approved</b> : Réalisateurs Kino 2016</p>';
						    
						    // echo '<p>according to in_array(), user '.$user->ID.' belongs to group X.</p>';
						    
						    // Add user info to array:
						    $kino_pending_real_kab[] = array( 
						        	"user-id" => $kino_userid,
						        	"user-name" => $user->display_name,
						        	"user-slug" => $user->user_nicename,
						        	"user-email" => $user->user_email,
						        	// "user-profile" => "Complet",
						        	"user-presentation" => bp_get_profile_field_data( array(
						        			'field'   => $kino_fields['id-presentation'],
						        			'user_id' => $kino_userid
						        	) ),
						        	"user-presentation-real" => bp_get_profile_field_data( array(
						        			'field'   => $kino_fields['profil-real-complete'],
						        			'user_id' => $kino_userid
						        	) ),
						        	"ville" => bp_get_profile_field_data( array(
						        			'field'   => $kino_fields["ville"],
						        			'user_id' => $kino_userid
						        	) ),
						        	"pays" => bp_get_profile_field_data( array(
						        			'field'   => $kino_fields["pays"],
						        			'user_id' => $kino_userid
						        	) )
						        	// "title" => get_the_title(),
						        	// "permalink" => get_permalink(),
						     );
						     
			
						} else if ( has_term( 
							$kino_fields['group-real-kabaret'],  // term ID to check for !!
							'user-group', // taxonomy name
							$kino_userid ) // post to check
						) {
						    
						    // Add user info to array:
						    $kino_valid_real_kab[] = array( 
						        	"user-id" => $kino_userid,
						        	"user-name" => $user->display_name,
						        	"user-slug" => $user->user_nicename,
						        	"user-email" => $user->user_email,
						        	// "user-profile" => "Incomplet",
						        	"user-presentation" => bp_get_profile_field_data( array(
						        			'field'   => $kino_fields['id-presentation'],
						        			'user_id' => $kino_userid
						        	) ),
						        	"user-presentation-real" => bp_get_profile_field_data( array(
						        			'field'   => $kino_fields['profil-real-complete'],
						        			'user_id' => $kino_userid
						        	) ),
						        	"ville" => bp_get_profile_field_data( array(
						        			'field'   => $kino_fields["ville"],
						        			'user_id' => $kino_userid
						        	) ),
						        	"pays" => bp_get_profile_field_data( array(
						        			'field'   => $kino_fields["pays"],
						        			'user_id' => $kino_userid
						        	) )
						        	// "title" => get_the_title(),
						        	// "permalink" => get_permalink(),
						     );
						     
						} else {
						
							// else :
							// is "Pending Réal" for Kabaret 2016?
							// ********************************
							// method = test xprofile field
							
							// Test des rôles pour le Kabaret 2016
							$kino16_particiation_boxes = bp_get_profile_field_data( array(
									'field'   => $kino_fields['role-kabaret'],
									'user_id' => $kino_userid
							) );
							// test field 135 = participation en tant que
							if ($kino16_particiation_boxes) {
								foreach ($kino16_particiation_boxes as $key => $value) {
								
									$value = mb_substr($value, 0, 4);
								
								  if ( $value == "Réal" ) {
								  	// echo '<p>user '.$user->user_login.' has requested : Réalisateurs Kino 2016</p>';
								  	
								  	// on peut 
								  	
								  	// Add user info to array:
								  	$kino_pending_real_kab[] = array( 
								  	    	"user-id" => $kino_userid,
								  	    	"user-name" => $user->display_name .' (xprofile field)',
								  	    	"user-slug" => $user->user_nicename,
								  	    	"user-email" => $user->user_email,
								  	    	// "user-profile" => "Incomplet",
								  	    	"user-presentation" => bp_get_profile_field_data( array(
								  	    			'field'   => $kino_fields['id-presentation'],
								  	    			'user_id' => $kino_userid
								  	    	) ),
								  	    	"user-presentation-real" => bp_get_profile_field_data( array(
								  	    			'field'   => $kino_fields['profil-real-complete'],
								  	    			'user_id' => $kino_userid
								  	    	) ),
								  	    	"ville" => bp_get_profile_field_data( array(
								  	    			'field'   => $kino_fields["ville"],
								  	    			'user_id' => $kino_userid
								  	    	) ),
								  	    	"pays" => bp_get_profile_field_data( array(
								  	    			'field'   => $kino_fields["pays"],
								  	    			'user_id' => $kino_userid
								  	    	) )
								  	    	// "title" => get_the_title(),
								  	    	// "permalink" => get_permalink(),
								  	 );
								  	
								  }
								  
								} // end foreach
							} // End if $kino16_participation_boxes
						
						
						} // End IF/ELSE structure
						
						// is Valid Réal for Plateforme Kino?
						// ********************************
						// method = test user-group
						
						// else :
						// is Pending Réal for Plateforme Kino?
						// ********************************
						// method = test xprofile field
						
					} // End foreach
				} // End testing User_Query
				
				
				// STEP 2: We have built our arrays - now we produce the output:
				
				// for $kino_pending_real_kab[]
				
				if (!empty($kino_pending_real_kab) ) {
						
						echo '<div>';
						echo '<h2>Réalisateurs-trices en attente : Kabaret 2016 ('.count($kino_pending_real_kab).')</h2>';
						echo '<div class="kino-admin-view">';
				
						foreach ($kino_pending_real_kab as $key => $item) {
						
								?>
								<div class="alert alert-warning kino-admin-view-item">
									<?php 
											echo '<b>';
											echo $item["user-name"].'</b>, ';
											echo $item["ville"].', ';
											echo $item["pays"];
											echo '<br/>';
									?><b>Email:</b> <a href="mailto:<?php echo $item["user-email"] ?>?Subject=Kino%20Kabaret" target="_top"><?php echo $item["user-email"] ?></a>
								
								<br/>
								<b>Présentation:</b> <?php echo $item["user-presentation"] ?>
								<br/>
								<b>Motivation:</b> <?php echo $item["user-presentation-real"] ?>
								<br/>
								
								<a href="<?php echo $url; ?>/members/<?php echo $item["user-slug"]; ?>/" target="_blank" class="btn btn-default">Voir le profil complet</a> 
								
								<a href="<?php echo $url; ?>/wp-admin/user-edit.php?user_id=<?php echo $item["user-id"] ?>#user-group" target="_blank" class="btn btn-default">Modifier groupes</a>
								<?php 
								/*
							*	<form id='useraction-id  echo $kino_pending_real_kab[$key]["user-id"] 
							* ' action='#' method='GET' class="hidden">
							*							    <input type='submit' name="action" value='Approuver' class="btn btn-success">
							*								    <input type='submit' name="action" value='Refuser' class="btn btn-danger">
							*								</form>
							*/
								 ?>
								</div>
							<?php 
						}
						echo '</div></div>';
				} // end looping array
				
				
				// for $kino_valid_real_kab[]
				
				if (!empty($kino_valid_real_kab) ) {
						
						echo '<div>';
						echo '<h2>Réalisateurs-trices validés : Kabaret 2016 ('.count($kino_valid_real_kab).')</h2>';
						echo '<div class="kino-admin-view">';
				
						foreach ($kino_valid_real_kab as $key => $item) {
						
								?>
								<div class="alert alert-success kino-admin-view-item">
									<?php 
											echo '<b>';
											echo $item["user-name"].'</b>, ';
											echo $item["ville"].', ';
											echo $item["pays"];
											echo '<br/>';
									?><b>Email:</b> <a href="mailto:<?php echo $item["user-email"] ?>?Subject=Kino%20Kabaret" target="_top"><?php echo $item["user-email"] ?></a>
									
									<br/>
									<b>Présentation:</b> <?php echo $item["user-presentation"] ?>
									<br/>
									<b>Motivation:</b> <?php echo $item["user-presentation-real"] ?>
									<br/>
									
									<a href="<?php echo $url; ?>/members/<?php echo $item["user-slug"]; ?>/" target="_blank" class="btn btn-default" target="_blank">Voir le profil complet</a> 
									
									<a href="<?php echo $url; ?>/wp-admin/user-edit.php?user_id=<?php echo $item["user-id"] ?>#user-group" target="_blank" class="btn btn-default" target="_blank">Modifier groupes</a>
									
								</div>
								
								
							<?php 
						}
						echo '</div></div>';
				} // end looping array
     ?>
        
    </div><!--end article-content-->

    <?php  ?>
</article>
<!-- End  Article -->