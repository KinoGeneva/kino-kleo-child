<?php
/**
 * Un template pour les Sessions
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
        
        $kino_debug_mode = 'off';
        
        $url = site_url();
        
        $kino_fields = kino_test_fields();
        
        $ids_real_kabaret_accepted = get_objects_in_term( 
        	$kino_fields['group-real-kabaret'] , 
        	'user-group' 
        );
        
        $ids_real_kabaret_accepted = array_filter($ids_real_kabaret_accepted);
        
        // user query
        //***************************************
        
        $user_query = new WP_User_Query( array( 
        	'include' => $ids_real_kabaret_accepted, // IDs incluses 
        	'orderby' => 'registered',
        	'order' => 'DESC' 
        ) );
        
        if ( ! empty( $user_query->results ) ) {
        	foreach ( $user_query->results as $user ) {
        			
        			// infos about WP_user object
        			$id = $user->ID ;
        			
        			// test session info
        			$kino_session_attrib = bp_get_profile_field_data( array(
        					'field'   => $kino_fields['session-attribuee'],
        					'user_id' => $id
        			) );
        			$kino_session_short = mb_substr($kino_session_attrib, 0, 9);
        			
        			if ( $kino_session_short == 'session 1' ) {
        				
        				$kino_session_un_title = $kino_session_attrib;
        				$kinoites_session_un[] = kino_user_fields_superlight( $user, $kino_fields );
        				
        			} else if ( $kino_session_short == 'session 2' ) {
        				
        				$kino_session_deux_title = $kino_session_attrib;
        				$kinoites_session_deux[] = kino_user_fields_superlight( $user, $kino_fields );
        				
        			} else if ( $kino_session_short == 'session 3' ) {
        				
        				$kino_session_trois_title = $kino_session_attrib;
        				$kinoites_session_trois[] = kino_user_fields_superlight( $user, $kino_fields );
        				
        			} else if ( $kino_session_short == 'session 4' ) {
        			
        				$kino_session_superhuit_title = $kino_session_attrib;
        				$kinoites_session_superhuit[] = kino_user_fields_superlight( $user, $kino_fields );
        			
        			} // end session testing
        			
        	} // End foreach
        } // End testing User_Query
        	
        //***************************************
        
        $kino_session_table_header = '<table class="table table-hover table-bordered table-condensed">
        	<thead>
        		<tr>
        			<th>#</th>
        			<th>Nom</th>
        	    <th>Email</th>
        		</tr>
        	</thead>
        	<tbody>';
        
        // OUTPUT!
        
        if ( !empty($kinoites_session_un) ) {
        	echo '<h2>'.count($kinoites_session_un).' réalisateurs-trices en '.$kino_session_un_title.':</h2>';
	        echo $kino_session_table_header;
        	$metronom = 1;
    				foreach ($kinoites_session_un as $key => $item) {
    						include('sessions-loop.php');
    				}
        	echo '</tbody></table>';
        }
        // Session 2
        if ( !empty($kinoites_session_deux) ) {
        	echo '<h2>'.count($kinoites_session_deux).' réalisateurs-trices en '.$kino_session_deux_title.':</h2>';
          echo $kino_session_table_header;
        	$metronom = 1;
        		foreach ($kinoites_session_deux as $key => $item) {
        				include('sessions-loop.php');
        		}
        	echo '</tbody></table>';
        }
        // Session 3
        if ( !empty($kinoites_session_trois) ) {
        	echo '<h2>'.count($kinoites_session_trois).' réalisateurs-trices en '.$kino_session_trois_title.':</h2>';
          echo $kino_session_table_header;
        	$metronom = 1;
        		foreach ($kinoites_session_trois as $key => $item) {
        				include('sessions-loop.php');
        		}
        	echo '</tbody></table>';
        }
        // Session 4
        if ( !empty($kinoites_session_superhuit) ) {
        	echo '<h2>'.count($kinoites_session_superhuit).' réalisateurs-trices en '.$kino_session_superhuit_title.':</h2>';
          echo $kino_session_table_header;
        	$metronom = 1;
        		foreach ($kinoites_session_superhuit as $key => $item) {
        				include('sessions-loop.php');
        		}
        	echo '</tbody></table>';
        }
        
        
        
         ?>
        
    </div><!--end article-content-->

    <?php  ?>
</article>
<!-- End  Article -->