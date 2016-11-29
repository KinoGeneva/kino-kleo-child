<?php

  		?>
  		<tr class="pending-candidate" data-id="<?php echo $user->ID; ?>">
  			<th><?php echo $metronom++; ?></th>
  			<?php 
  			
  					// $kino_user_role = kino_user_participation( $user->ID, $kino_fields );
  					
  					// user ID
  					echo '<td>'.$user->ID.'</td>';
  					
  					// Nom 
  					
  					echo '<td>';
  					echo '<a href="'.$url.'/members/'.$user->user_nicename.'/" target="_blank">';
  					
  					if ( empty($user->display_name) ) {
  						echo $user->user_nicename;
  					} else {
  						echo $user->display_name;
  					}

  					echo '</a>';
  					
  					// Email
  			echo ' — <a href="mailto:'. $user->user_email .'?Subject=Kino%20Kabaret" target="_top">'. $user->user_email .'</a></td>';
  					
      			$kino_user_role = kino_user_participation( 
      				$user->ID, 
      				$kino_fields
      			);
      			
      			// Rôles Kino
      			// ******************
      			
      			echo '<td>'; 
      			
      				// Réalisateur ?
      				if ( in_array( "realisateur-2016", $kino_user_role )) {
      					echo '<span class="kp-pointlist">Réalisateur-trice</span>';
      				}
      				// Technicien ?
      				if ( in_array( "technicien-2016", $kino_user_role )) {
      					echo '<span class="kp-pointlist">Artisan-ne / technicien-ne</span>';
      				}
      				// Comédien ?
      				if ( in_array( "comedien-2016", $kino_user_role )) {
      					echo '<span class="kp-pointlist">Comédien-ne</span>';
      				}
      				
      				// Afficher la session!
      				// ['session-attribuee']
      				if ( in_array( "realisateur-2016", $kino_user_role )) {
	      				$kino_session_attrib = bp_get_profile_field_data( array(
	      						'field'   => $kino_fields['session-attribuee'],
	      						'user_id' => $user->ID
	      				) );
	      				$kino_session_attrib = mb_substr($kino_session_attrib, 0, 9);
	      				if (!empty($kino_session_attrib) ) {
	      					echo '<span class="kp-pointlist"><b>'.ucfirst($kino_session_attrib).'</b></span>';
	      				}
	      			}	
      				
      				if ( $user->ID == 420 ) {
      				
//		      				$kino_participation_xfield = bp_get_profile_field_data( array(
//		      					'field'   => $kino_fields['profile-role'],
//		      					'user_id' => $user->ID
//		      				) );
//      				
//		      				echo 'Role Plateforme:<pre>';
//		      				var_dump($kino_participation_xfield);
//		      				echo '</pre>';
//		      				
//		      				$kino_participation_xfield = bp_get_profile_field_data( array(
//		      							'field'   => $kino_fields['role-kabaret'],
//		      							'user_id' => $user->ID
//		      						) );
//		      				
//      						echo 'Role Kabaret:<pre>';
//      						var_dump($kino_participation_xfield);
//      						echo '</pre>';

//									kino_add_to_mailpoet_list( 
//										$id, 
//										$kino_fields['mailpoet-real-kabaret'] 
//									);
									
//									$helper_user = WYSIJA::get('user','helper');
//									
//									$mail_lists = array($kino_fields['mailpoet-real-kabaret']);
									
//									$kino_user_subscribe = $helper_user->addToLists(
//									    $mail_lists,
//									    $user->ID
//									 );

//									$helper_user->addToList(
//									    $kino_fields['mailpoet-real-kabaret'],
//									    array($user->ID)
//									 );
									 
									 // echo "subscribed = " . ( $kino_user_subscribe ? "true" : "false" );      					
      				}
      				
      			echo '</td>';
      				
      			
      			// Participe commme Réal Plateforme ?	
      			// ********************
      			
      			if ( in_array( "real-platform-valid", $kino_user_role ) ) {          				            				
    				  echo '<td class="success">Accepté</td>';
    				  
    				  // add checkbox!
    				  // kino_check_real_platform_checkbox( $user->ID, $kino_fields );
    				
    				} else if ( in_array( "real-platform-rejected", $kino_user_role ) ) {
    				
    				  echo '<td class="danger">Refusé</td>';
    				  
    				  // add users to mailpoet list:
//    				   	kino_add_to_mailpoet_list( $user->ID, 
//    				   		$kino_fields['mailpoet-real-platform-rejected'] 
//    				   	);
    				
    				} else if ( in_array( "real-platform-pending", $kino_user_role ) ) {
    				
    					echo '<td class="warning">En attente</td>';
    					// add checkbox!
    					 // kino_check_real_platform_checkbox( $user->ID, $kino_fields );
    				
    				} else {

    					echo '<td></td>';
    				}
      			            			
      			// Participe commme Réal Kab ?
      			// ********************
      			
      			// Test if : 
      				
    				if ( in_array( "real-kab-valid", $kino_user_role ) ) {          				            				
    				  echo '<td class="success">Accepté</td>';
    				
    				} else if ( in_array( "real-kab-rejected", $kino_user_role ) ) {
    				
    				  echo '<td class="danger">Refusé</td>';
    				  
    				  // remove checkbox!
    				  // kino_remove_real_kabaret_checkbox( $user->ID, $kino_fields );
    				
    				} else if ( in_array( "real-kab-pending", $kino_user_role ) ) {
    				
    					echo '<td class="warning">En attente</td>';
    					
      				//	kino_remove_from_mailpoet_list( $user->ID,
      				//	$kino_fields['mailpoet-real-platform-only'] );
    				
    				} else {

    					echo '<td></td>';
    				}
      			
      			// Profil complet ?	
      			// ********************
      			
      			if ( in_array( $user->ID, $ids_of_kino_complete ) ) {          				            				
      			  echo '<td class="success">Complet</td>';
      			} else {
      				echo '<td></td>';	
      			}
      			
      			// Registration date
      			$shortdate = substr( $user->user_registered, 0, 10 );
      			echo '<td>'. $shortdate .'</td>';
      			
      			// Actions
      			
      				if ( $kino_show_validation == 'kabaret' ) {
      			 
      			 		echo '<td>';
      			 			echo '<a class="admin-action pending-accept" data-action="kabaret-accept">accepter</a>';
      			 			echo '<a class="admin-action pending-reject" data-action="kabaret-reject">refuser</a>'; 
      			 		echo '</td>'; 
      			 		
      			 } else if ( $kino_show_validation == 'kabaret-plus' ) {
      			 
      			 		echo '<td>';
      			 			echo '<a class="admin-action pending-other" data-action="kabaret-moyen">moyen</a>';
      			 			echo '<a class="admin-action pending-other" data-action="kabaret-bien">bien</a>';
      			 			echo '<a class="admin-action pending-accept" data-action="kabaret-accept">accepter</a>';
      			 			echo '<a class="admin-action pending-reject" data-action="kabaret-reject">refuser</a>'; 
      			 		echo '</td>'; 
      			 		
      			 } else if ( $kino_show_validation == 'plateforme' ) {
      			 
      			 		echo '<td>';
      			 			echo '<a class="admin-action pending-accept" data-action="platform-accept">accepter</a>';
      			 			echo '<a class="admin-action pending-reject" data-action="platform-reject">refuser</a>';
      			 		echo '</td>'; 
      			 
      			 }
      			 			
  		echo '</tr>';
  		

