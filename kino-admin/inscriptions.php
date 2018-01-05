<?php
/**
 * Un template pour valider les inscriptions
 */
?>

<?php
$extra_classes = 'clearfix';
if ( get_cfield( 'centered_text' ) == 1 )  {
    $extra_classes .= ' text-center';
}

// Delete unwanted users:
// wp_delete_user( 90, 8 );


?>

<!-- Begin Article -->
<article id="post-<?php the_ID(); ?>" <?php post_class($extra_classes); ?>>

    <div class="article-content">
        <?php the_content(); ?>
        
        <?php 
        
        /*
         * Une page pour faciliter la gestion des inscriptions kino
        ***************
        ****/
        
        $kino_debug_mode = 'off';
        $url = site_url();
        $kino_fields = kino_test_fields();
        
        // On montre les membres faisant partie du groupe: 
        // Participants Kino 2016 : profil complet
        
        $ids_of_kino_participants = get_objects_in_term( 
        	$kino_fields['group-kino-pending'] , 
        	'user-group' 
        );
        
        $ids_of_kino_complete = get_objects_in_term( 
        	$kino_fields['group-kino-complete'] , 
        	'user-group' 
        );
        
        $ids_of_kino_realisateurs = get_objects_in_term( 
        	$kino_fields['group-real-kabaret'] , 
        	'user-group' 
        );
        
        $ids_of_kino_comediens = get_objects_in_term( 
        	$kino_fields['group-comp-comedien'] , 
        	'user-competences' 
        );
        
        $ids_of_kino_techniciens = get_objects_in_term(
        	$kino_fields['group-comp-technicien'] , 
        	'user-competences' 
        );
        //inscription
        $ids_of_paid_25 = get_objects_in_term(
        	$kino_fields['compta-paid-25'] , 
        	'user-compta' 
        );
         $ids_of_paid_offert_25 = get_objects_in_term(
        	$kino_fields['compta-paid-offert-25'] , //offert: inscription 25.-
        	'user-compta' 
        );
        $ids_of_paid_40 = get_objects_in_term(
        	$kino_fields['compta-paid-40'] , //tarif de soutien
        	'user-compta' 
        );
        $ids_of_paid_100 = get_objects_in_term(
        	$kino_fields['compta-paid-100'] , 
        	'user-compta' 
        );
        $ids_of_paid_125 = get_objects_in_term(
        	$kino_fields['compta-paid-125'] , //inscription 125.-
        	'user-compta' 
        );
         $ids_of_paid_offert_125 = get_objects_in_term( 
        	$kino_fields['compta-paid-offert-125'] , //offert:inscription 125.-
        	'user-compta' 
        );

        //repas
        $ids_of_repas_60 = get_objects_in_term( 
        	$kino_fields['compta-repas-60'] , 
        	'user-compta' 
        );
        $ids_of_repas_offert_60 = get_objects_in_term( 
        	$kino_fields['compta-repas-offert-60'] , //offert 60.- repas
        	'user-compta' 
        );
        $ids_of_repas_100 = get_objects_in_term( 
        	$kino_fields['compta-repas-100'] , 
        	'user-compta' 
        );
        $ids_of_repas_125 = get_objects_in_term( 
        	$kino_fields['compta-repas-125'] , //repas 125.-
        	'user-compta' 
        );
         $ids_of_repas_offert_125 = get_objects_in_term( 
        	$kino_fields['compta-repas-offert-125'] , //offert 125.- repas
        	'user-compta' 
        );
        
         
//        $ids_of_kino_participants = get_objects_in_term( 
//        	$kino_fields['group-kino-complete'] , 
//        	'user-group' 
//        );
        
        $ids_of_kino_participants = array_filter($ids_of_kino_participants);
        $ids_of_kino_complete = array_filter($ids_of_kino_complete);
        
        echo '<h3>Total des participants: '.count( $ids_of_kino_participants ) .'</h3>';
        
//        echo '<p>Total des participants au profil complet: '.count( $ids_of_kino_complete );
//        
//        $kino_complete_percentage = round( ( count( $ids_of_kino_complete ) / count( $ids_of_kino_participants ) ) * 100 );
//        
//        echo ' ('.$kino_complete_percentage.'%)</p>';
        
        // echo '<p><b>Note: </b> Ce tableau liste tous les '.count( $ids_of_kino_participants ) .' utilisateurs qui ont coché la participation au Kabaret 2016.</p>';
        	
        // echo '<p><b>Voir aussi les <a href="'.$url.'/kino-admin/membres-hors-kabaret/">membres hors-Kabaret</a>.</b></p>';
        // Voir Participants Kabaret pour une vue plus détaillée	
        	
        $user_fields = array( 
        	'user_login', 
        	'user_nicename', // = slug
        	'display_name',
        	'user_email', 
        	'ID',
        	'registered', 
        );
        
        $transientname = 'kinoites_inscription_users';
                		
    		if ( false === ( $user_query = get_transient( $transientname ) ) ) {
    		
    		    // It wasn't there, so regenerate the data and save the transient
    		     $user_query = new WP_User_Query( array( 
    		     	// 'fields' => $user_fields,
    		     	'include' => $ids_of_kino_participants,
    		     	'orderby' => 'display_name', // nicename
    		     	'order' => 'ASC'
    		     ) );
    		     
    		     set_transient( $transientname, $user_query, 60 );
    		     //  * HOUR_IN_SECONDS
    		     
    		     echo '<p>we just defined transient '.$transientname.'</p>';
    		}
        
        //***************************************
       
        if ( ! empty( $user_query->results ) ) {
        
        // Contenu du tableau
        	// Nom
        	// email
        	// Init:
        	$metronom = 1;
        	
        	$somme_paiements = 0;
        	$somme_paiements_offert = 0;
        	$somme_repas = 0;
        	$somme_repas_offert = 0;
        	
        	$nombre_entrees_25 = 0;
        	$nombre_entrees_offert_25 = 0;
        	$nombre_entrees_40 = 0;
        	$nombre_entrees_100 = 0;
        	$nombre_entrees_125 = 0;
        	$nombre_entrees_offert_125 = 0;
        	
        	$nombre_repas_60 = 0;
        	$nombre_repas_offert_60 = 0;
        	$nombre_repas_100 = 0;
        	$nombre_repas_125 = 0;
        	$nombre_repas_offert_125 = 0;
        	
        	?>
        	<div id="table-container">
        	<table id="inscription-table" class="table table-hover table-bordered table-condensed pending-form">
        		<thead>
        			<tr>
        				<th>#</th>
        				<th>ID</th>
        				<th width="200">Nom/Email</th>
        				<th>Profil complet?</th>
						<th width="200">Rôle Kabaret</th>
						<th>Réal?</th>
						<th>Inscription<br/>payée</th>
						<th>Action<br/>Inscription</th>
						<th>Carte Repas<br/>payée</th>
						<th>Action<br/>Carte repas</th>
						<th>Offerts</th>
						<th>Action offrir</th>
        			</tr>
        		</thead>
        		<tbody>
        		<?php
        
        	foreach ( $user_query->results as $user ) {
        		
        		?>
        		<tr class="inscription-kino pending-candidate" data-id="<?php echo $user->ID; ?>">
        			<th><?php echo $metronom++; ?></th>
        			<?php 
        					
        					$id = $user->ID;
        					
//        					$kino_user_role = kino_user_participation( 
//        						$id, 
//        						$kino_fields
//        					);
        					
        					// ID
        					echo '<td>'.$id.'</td>';
        					
        					// Name
        					echo '<td>';
        					
        					if ( !empty($user->display_name) ) {
        						echo $user->display_name .'<br/>';
        					}
        					
        					echo '<a href="'.$url.'/members/'.$user->user_nicename.'/profile/" target="_blank">';
		        					echo $user->user_nicename;
        					echo '</a><br/>';
        					
        					// Email
							echo '<a href="mailto:'. $user->user_email .'?Subject=Kino%20Kabaret" target="_top">'. $user->user_email .'</a></td>';
        					
        					
        					// Profil complet ?
        					// ******************
        					
        					// Test if : 
        					
        					if ( in_array( $id, $ids_of_kino_complete ) ) {          				            				
        					  echo '<td class="success">Complet</td>';
        					} else {
        						echo '<td></td>';	
        					}
        					
        					
        					// Rôles Kino
        					// ******************
        					
        					echo '<td>'; 
        					
        						// Réalisateur ?
        						if ( in_array( $id, $ids_of_kino_realisateurs )) {
        							echo '<span class="kp-pointlist">Réalisateur-trice</span>';
        						}
        						// Technicien ?
        						if ( in_array( $id, $ids_of_kino_techniciens )) {
        							echo '<span class="kp-pointlist">Artisan-ne / technicien-ne</span>';
        						}
        						// Comédien ?
        						if ( in_array( $id, $ids_of_kino_comediens )) {
        							echo '<span class="kp-pointlist">Comédien-ne</span>';
        						}
        						
        					echo '</td>';
        					
            			            			
            			// Participe commme Réal ?
            			// ******************
            			
            			// Test if : 
            				
          				if ( in_array( $id, $ids_of_kino_realisateurs ) ) { 
          				  echo '<td class="success">Réalisateur-trice</td>';
          				} else {
          					echo '<td></td>';
          				}
            			
            			// Actions Inscription!!!
            			// ***********************

            			echo '<td>';

	            			if ( in_array( $id, $ids_of_paid_25 ) ) {
	            				echo '<span class="has-paid">Payé: 25.-</span>';
	            				$somme_paiements = ( $somme_paiements + 25) ;
	            				$nombre_entrees_25++;
	            			}
	            			
	            			if ( in_array( $id, $ids_of_paid_40 ) ) {
	            				echo '<span class="has-paid">Payé: 40.-</span>';
	            				$somme_paiements = ( $somme_paiements + 40) ;
	            				$nombre_entrees_40++;
	            			}
	            			
	            			if ( in_array( $id, $ids_of_paid_100 ) ) {
	            				echo '<span class="has-paid">Payé: 100.-</span>';
	            				$somme_paiements = ( $somme_paiements + 100) ;
	            				$nombre_entrees_100++;
	            			}
	            			
	            			if ( in_array( $id, $ids_of_paid_125 ) ) {
	            				echo '<span class="has-paid">Payé: 125.-</span>';
	            				$somme_paiements = ( $somme_paiements + 125) ;
	            				$nombre_entrees_125++;
	            			}	
            			echo '</td>';
            			echo '<td>';
	            			if ( in_array( $id, $ids_of_paid_25 ) || in_array( $id, $ids_of_paid_40 ) || in_array( $id, $ids_of_paid_100 ) || in_array( $id, $ids_of_paid_125 ) ) {
	            				
	            				echo '<a class="admin-action payment-reset pending-reject" data-action="payment-reset">Reset</a>';
	            			} else {
	            				
	            				echo '<a class="admin-action payment-25 pending-other" data-action="payment-25">Payer 25</a>';
	            				echo '<a class="admin-action payment-40 pending-other" data-action="payment-40">Payer 40</a>';
	            				echo '<a class="admin-action payment-100 pending-other" data-action="payment-100">Payer 100</a>';
	            				echo '<a class="admin-action payment-125 pending-other" data-action="payment-125">Payer 125</a>';
	            			}
      	
            			echo '</td>';
            			
            			// Actions Carte Repas
            			// ***********************
            				
            			echo '<td>';
            				
            				if ( in_array( $id, $ids_of_repas_60 ) ) {
            					echo '<span class="has-paid">Payé: 60.-</span>';
            					$somme_repas = ( $somme_repas + 60 );
            					$nombre_repas_60++; 
            				}
            				
            				if ( in_array( $id, $ids_of_repas_100 ) ) {
            					echo '<span class="has-paid">Payé: 100.-</span>';
            					$somme_repas = ( $somme_repas + 100 );
            					$nombre_repas_100++; 
            				}
            				
            				if ( in_array( $id, $ids_of_repas_125 ) ) {
            					echo '<span class="has-paid">Payé: 125.-</span>';
            					$somme_repas = ( $somme_repas + 125 );
            					$nombre_repas_125++; 
            				}
						echo '</td>';
						echo '<td>';
            				if ( in_array( $id, $ids_of_repas_60 ) || in_array( $id, $ids_of_repas_100 ) || in_array( $id, $ids_of_repas_125 ) ) {
            					
            					echo '<a class="admin-action repas-reset pending-reject" data-action="repas-reset">Reset</a>';
            				}
            				else {
            					echo '<a class="admin-action repas-60 pending-other" data-action="repas-60">Carte 60</a>';
            					echo '<a class="admin-action repas-100 pending-other" data-action="repas-100">Carte 100</a>';
            					echo '<a class="admin-action repas-125 pending-other" data-action="repas-125">Carte 125</a>';
            				}
            			echo '</td>';
            			//offert
            			echo '<td>';
							if ( in_array( $id, $ids_of_paid_offert_25 ) ) {
	            				echo '<span class="has-paid">Offert inscription: 25.-</span>';
	            				$somme_paiements_offert = ( $somme_paiements_offert + 25) ;
	            				$nombre_entrees_offert_25++;
	            			}
							if ( in_array( $id, $ids_of_paid_offert_125 ) ) {
	            				echo '<span class="has-paid">Offert inscription: 125.-</span>';
	            				$somme_paiements_offert = ( $somme_paiements_offert + 125) ;
	            				$nombre_entrees_offert_125++;
	            			}	
							if ( in_array( $id, $ids_of_repas_offert_60 ) ) {
            					echo '<span class="has-paid">Offert repas: 60.-</span>';
            					$somme_repas_offert = ( $somme_repas_offert + 60 );
            					$nombre_repas_offert_60++; 
            				}
            				if ( in_array( $id, $ids_of_repas_offert_125 ) ) {
            					echo '<span class="has-paid">Offert repas: 125.-</span>';
            					$somme_repas_offert = ( $somme_repas_offert + 125 );
            					$nombre_repas_offert_125++; 
            				}
            			echo '</td>';
            			echo '<td>';
            				if ( in_array( $id, $ids_of_paid_offert_25 ) || in_array( $id, $ids_of_paid_offert_125 ) ) {
            					echo '<a class="admin-action offert-reset pending-reject" data-action="offert-entree-reset">Reset entrée</a><br/>';
            				}
            				else {
								echo '<a class="admin-action offert-25 pending-accept" data-action="offert-entree-25">25.- offert</a>';
								echo '<a class="admin-action offert-125 pending-accept" data-action="offert-entree-125">125.- (entrée) offert</a>';
            				}
            				if ( in_array( $id, $ids_of_repas_offert_60 ) || in_array( $id, $ids_of_repas_offert_125 ) ) {
            					echo '<a class="admin-action offert-reset pending-reject" data-action="offert-repas-reset">Reset repas</a>';
            				}
            				else {
            					echo '<a class="admin-action offert-60 pending-accept" data-action="offert-repas-60">60.- offert</a>';
            					echo '<a class="admin-action offert-125 pending-accept" data-action="offert-repas-125">125.- (repas) offert</a>';
            				}
            			echo '</td>';
        			
        		echo '</tr>';
        		
        	}
        	echo '</tbody>';
        			// Somme des Payements:
        			//éviter que les totaux soient déplacés par les filtres
        	echo '<tfoot>';
        			?>
        			<tr>
						<th colspan="5"></th>
						<th>Somme Inscriptions: </th>
						<?php 
        			  	// Somme Inscriptions
        			  	echo '<th><span class="has-paid">'.$somme_paiements.'.-</span></th>'; ?>
						<th>Somme Cartes Repas: </th>
						<?php 
        			  	// Somme Carte Repas 
        			  	echo '<th><span class="has-paid">'.$somme_repas.'.-</span></th>'; ?>
        			  	</th>
						<th colspan="3"></th>
        			</tr>
        			<tr>
						<th colspan="5"></th>
						<th>Somme inscriptions offertes</th>
						<?php
						// Somme Inscriptions offertes
        			  	echo '<th><span class="has-paid">'.$somme_paiements_offert.'.-</span></th>'; ?>
						<th>Somme repas offerts</th>
						<?php
						// Somme Inscriptions offertes
        			  	echo '<th><span class="has-paid">'.$somme_paiements_offert.'.-</span></th>'; ?>
        			  	<th colspan="3"></th>
        			</tr>
        			<tr>
						<th colspan="5"></th>
						<th>Sommes totale inscriptions (payé + offert)</th>
						<?php
						// Somme totale Inscriptions
        			  	echo '<th><span class="has-paid">'. ($somme_paiements + $somme_paiements_offert) .'.-</span></th>'; ?>
        			  	<th>Sommes totale repas (payé + offert)</th>
        			  	<?php
						// Somme totale Repas
        			  	echo '<th><span class="has-paid">'. ($somme_repas + $somme_repas_offert) .'.-</span></th>'; ?>
        			  	<th colspan="3"></th>
        			</tr>
        			<tr>
						<th colspan="5"></th>
						<th colspan="4">
							<?php
							// Somme Totale payée
							$somme_totale = ( $somme_paiements + $somme_repas );
							echo '<div><b>Somme Totale payée: '.$somme_totale.'.-</b></div>';

							// Somme Totale offerte
							$somme_totale_offert = ( $somme_paiements_offert + $somme_repas_offert );
							echo '<div"><b>Somme Totale offerte: '.$somme_totale_offert .'.-</b></div>';
							
							// Somme Totale
							echo '<div><b>Somme Totale: '. ($somme_totale + $somme_totale_offert) .'.-</b></div>';
							?>
						</th>
						<th colspan="3"></th>
        			</tr>
        			<tr>
						<?php
						$all_days_compta = array();
						$term_ids =  array( $kino_fields['compta-paid-25'], $kino_fields['compta-paid-40'], $kino_fields['compta-paid-100'], $kino_fields['compta-paid-125'], $kino_fields['compta-repas-60'], $kino_fields['compta-repas-100'], $kino_fields['compta-repas-125'], $kino_fields['compta-paid-offert-25'], $kino_fields['compta-paid-offert-125'], $kino_fields['compta-repas-offert-60'], $kino_fields['compta-repas-offert-125']);
						foreach( $term_ids as $term_id ){
							$all_compta_each_days[$term_id] = get_term_meta( $term_id );
						}
						/*
						echo '<pre>';
						print_r($all_compta_each_days);
						echo '</pre>';
						*/
						// todo: entrées / carte / offert par jour ?>
        			</tr>
        			<?php
        	echo '</tfoot></table></div>';


//nombres totaux et par jour
echo '<h4>Entrées à 25 : '. $nombre_entrees_25. '</h4>
	<ul>';
foreach($all_compta_each_days[$kino_fields['compta-paid-25']] as $day => $total){
	echo '<li>';
		echo $day .': '. $total[0];
	echo '</li>';
}
echo '</ul>';

echo '<h4>Entrées offertes à 25 : '.$nombre_entrees_offert_25.' </h4>
	<ul>';
foreach($all_compta_each_days[$kino_fields['compta-paid-offert-25']] as $day => $total){
	echo '<li>';
		echo $day .': '. $total[0];
	echo '</li>';
}
echo '</ul>';

echo '<h4>Entrées à 40 : '.$nombre_entrees_40 .' </h4>
	<ul>';
foreach($all_compta_each_days[$kino_fields['compta-paid-40']] as $day => $total){
	echo '<li>';
		echo $day .': '. $total[0];
	echo '</li>';
}
echo '</ul>';

echo '<h4>Entrées à 100 : '.$nombre_entrees_100 .' </h4>
	<ul>';
foreach($all_compta_each_days[$kino_fields['compta-paid-100']] as $day => $total){
	echo '<li>';
		echo $day .': '. $total[0];
	echo '</li>';
}
echo '</ul>';

echo '<h4>Entrées à 125 : '.$nombre_entrees_125 .' </h4>
	<ul>';
foreach($all_compta_each_days[$kino_fields['compta-paid-125']] as $day => $total){
	echo '<li>';
		echo $day .': '. $total[0];
	echo '</li>';
}
echo '</ul>';

echo '<h4>Entrées offertes à 125 : '.$nombre_entrees_offert_125 .' </h4>
	<ul>';
foreach($all_compta_each_days[$kino_fields['compta-paid-offert-125']] as $day => $total){
	echo '<li>';
		echo $day .': '. $total[0];
	echo '</li>';
}
echo '</ul>';

echo '<h4>Cartes Repas à 60 : '.$nombre_repas_60 .' </h4>
	<ul>';
foreach($all_compta_each_days[$kino_fields['compta-repas-60']] as $day => $total){
	echo '<li>';
		echo $day .': '. $total[0];
	echo '</li>';
}
echo '</ul>';

echo '<h4>Cartes Repas offertes à 60 : '.$nombre_repas_offert_60 .' </h4>
	<ul>';
foreach($all_compta_each_days[$kino_fields['compta-repas-offert-60']] as $day => $total){
	echo '<li>';
		echo $day .': '. $total[0];
	echo '</li>';
}
echo '</ul>';

echo '<h4>Cartes Repas à 100 : '.$nombre_repas_100 .' </h4>
	<ul>';
foreach($all_compta_each_days[$kino_fields['compta-repas-100']] as $day => $total){
	echo '<li>';
		echo $day .': '. $total[0];
	echo '</li>';
}
echo '</ul>';

echo '<h4>Cartes Repas à 125 : '.$nombre_repas_125 .' </h4>
	<ul>';
foreach($all_compta_each_days[$kino_fields['compta-repas-125']] as $day => $total){
	echo '<li>';
		echo $day .': '. $total[0];
	echo '</li>';
}
echo '</ul>';

echo '<h4>Cartes Repas offertes à 125 : '.$nombre_repas_offert_125 .' </h4>
	<ul>';
foreach($all_compta_each_days[$kino_fields['compta-repas-offert-125']] as $day => $total){
	echo '<li>';
		echo $day .': '. $total[0];
	echo '</li>';
}
echo '</ul>';



        	// Ajouter à Mailpoet: Participants Kabaret
//        	kino_add_to_mailpoet_list( 
//        	 	$ids_of_kino_complete, 
//        	 	$kino_fields['mailpoet-participant-kabaret'] 
//        	 	);
        
        } // test !empty
        
         ?>
        
    </div><!--end article-content-->
  
    <?php  ?>
</article>
<!-- End  Article -->

<?php
kino_js_tablesort("inscription-table");
