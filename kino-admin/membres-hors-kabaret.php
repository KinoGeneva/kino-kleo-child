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
?>

<!-- Begin Article -->
<article id="post-<?php the_ID(); ?>" <?php post_class($extra_classes); ?>>

    <div class="article-content">
        <?php the_content(); ?>
        
        <?php 
        
        /*
         Vue: tous les membres hors Kabaret
         tous les membres ne participant pas au cabaret
         Voir: date d'inscription.
         = permet de vérifier qu'il n'y ait pas de manquant à l'appel
        
        ****/
        	
        $kino_debug_mode = 'off';
        $url = site_url();
        $kino_fields = kino_test_fields();
        
        // On enlève les membres faisant partie du groupe: 
        // Participants Kino 2016 : profil complet
        
        $ids_of_kino_complete = get_objects_in_term( 
        	$kino_fields['group-kino-complete'] , 
        	'user-group' 
        );
        
        $ids_of_kino_complete = array_filter($ids_of_kino_complete);
        
        echo '<h3>Total des participants au Kabaret (profil complet): '.count($ids_of_kino_complete).'</h3>';
        
        echo '<p><b>Voir <a href="'.$url.'/kino-admin/participants-kabaret/">Participants Kabaret</a> pour une vue plus détaillée.</b></p>';
        
        if ( $kino_debug_mode == 'on' ) {
        	echo '<pre>';
        	var_dump($ids_of_kino_complete);
        	echo '</pre>';
        }
        
        $user_fields = array( 
        	'user_login', 
        	'user_nicename', // = slug
        	'display_name',
        	'user_email', 
        	'ID',
        	'registered', 
        );
        
        $user_query = new WP_User_Query( array( 
        	// 'fields' => $user_fields,
        	'exclude' => $ids_of_kino_complete,
        	'orderby' => 'registered',
        	'order' => 'DESC'
        ) );

        //***************************************
        
        // Quel est le total d'utilisateurs?
        
        echo '<h3>Total des utilisateurs hors Kabaret: '.count($user_query->results).'</h3>';
        
        echo '<p><b>Note: </b> Ce tableau liste tous les utilisateurs qui ne sont PAS dans le groupe "Participants Kino 2016 : profil complet". Il inclut donc les usagers ayant coché la participation, mais dont le profil n’est pas encore complet.</p>';
        
        echo '<p><b>Voir aussi la <a href="'.$url.'/kino-admin/participants-kabaret/">liste des participants au Kabaret</a>.</b></p>';
        
        if ( ! empty( $user_query->results ) ) {
        
        // Contenu du tableau
        	// Nom
        	// email
        	// Init:
        	$metronom = 1;
        	
        	$count_participants_kabaret = 0;
        	
        	?>
        	<table class="table table-hover table-bordered table-condensed">
        		<thead>
        			<tr>
        				<th>#</th>
        				<th>Nom</th>
        		    <th>Email</th>
        		    <th>Kab 2016?</th>
        		    <th>Inscription</th>
        			</tr>
        		</thead>
        		<tbody>
        		<?php
        
        	foreach ( $user_query->results as $user ) {
        		
        		?>
        		<tr>
        			<th><?php echo $metronom++; ?></th>
        			<?php 
        			
        					// $user->ID
        					echo '<td><a href="'.$url.'/members/'.$user->user_nicename.'/" target="_blank">'.$user->user_login.'</a> ('.$user->display_name.')</td>';
        					
        					// Email
        			echo '<td><a href="mailto:'. $user->user_email .'?Subject=Kino%20Kabaret" target="_top">'. $user->user_email .'</a></td>';
        			
		        			// Participe au Kabaret 2016?
		        			$kino_test = bp_get_profile_field_data( array(
		        					'field'   => $kino_fields['kabaret'], 
		        					'user_id' => $user->ID
		        			) );
		        			if ( ( $kino_test == "oui" ) || ( $kino_test == "yes" ) ) {
		        						echo '<td class="success">OUI</td>';
		        						// Ajouter à Mailpoet: profil incomplet
//		        						kino_add_to_mailpoet_list( 
//		        						 	$user->ID, 
//		        						 	$kino_fields['mailpoet-participant-kabaret-incomplet'] 
//		        						 	);
		        						// increment counter:
		        						$count_participants_kabaret++;
		        			} else {
		        						echo '<td>NON</td>';
		        			}
		        			
		        			
		        			// Registration date
		        			echo '<td>'. $user->user_registered .'</td>';
        			
        		echo '</tr>';
        		
        	}
        	
        	echo '</tbody></table>';
        }
        
        echo '<p>Nombre de participants incomplets inscrits au Kabaret 2016: <b>'.$count_participants_kabaret.'</b></p>';
        
         ?>
        
    </div><!--end article-content-->

    <?php  ?>
</article>
<!-- End  Article -->