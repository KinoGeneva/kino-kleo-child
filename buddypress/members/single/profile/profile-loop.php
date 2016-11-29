<?php do_action( 'bp_before_profile_loop_content' ); ?>

<?php if ( bp_has_profile() ) : 
	
	echo '<div class="bp-profile-loop-container">';

	// NOTE: 
	// Ici, nous empêchons les utilisateurs de voir certains groupes de champs:
	
	// 1) Liste des groupes existants
	
	$groups = wp_cache_get( 'all', 'bp_xprofile_groups' );
	if ( false === $groups ) {
		$groups = bp_xprofile_get_groups( array( 'fetch_fields' => true ) );
		wp_cache_set( 'all', $groups, 'bp_xprofile' );
	}
	
	// 2) Liste des groupes autorisés (filtrés)
	
	$kino_allowed_groups = kino_get_field_group_conditions( $groups );
	$kino_allowed_group_ids = array();
	
	foreach( $kino_allowed_groups as $item ){ 
	  $kino_allowed_group_ids[] = $item->id;
	   			//echo $item->name;
	}
	
	
	// 3) Liste de champs autorisés:
	
	$kino_excluded_fields = kino_list_of_excluded_profile_fields();
	
//	echo '<pre>Allowed ids:';
//	var_dump($kino_allowed_group_ids);
//	echo '</pre>';

?>

	<?php while ( bp_profile_groups() ) : bp_the_profile_group(); 
	
			// test if group ID... bp_the_profile_group_id() 
			
			// Affichage conditionnel: on n'affiche que si le groupe est autorisé
			// echo '<p>group id:'.bp_get_the_profile_group_id() .'</p>';
			
			if ( in_array( bp_get_the_profile_group_id(), $kino_allowed_group_ids ) ) :
			
			 // echo '<p>(showing group:'.bp_get_the_profile_group_id() .': '.bp_get_the_profile_group_name().')</p>';
	?>

		<?php if ( bp_profile_group_has_fields() ) : ?>

			<?php do_action( 'bp_before_profile_field_content' ); ?>

			<div class="bp-widget <?php bp_the_profile_group_slug(); ?>">
		
				<div class="hr-title hr-full hr-double"><abbr><?php bp_the_profile_group_name(); ?></abbr></div>
				<div class="gap-10"></div>
				
				<div class="bp-profile-loop">
				
					<?php while ( bp_profile_fields() ) : bp_the_profile_field(); 
					
						if ( bp_field_has_data() ) : 
						
								if ( !in_array( bp_get_the_profile_field_id(), $kino_excluded_fields ) ) :
						?>
              
              <dl<?php bp_field_css_class('dl-horizontal'); ?>>
                <dt><?php bp_the_profile_field_name(); ?></dt>
                <dd><?php bp_the_profile_field_value(); ?></dd>
              </dl>

						<?php endif;  // field is allowed
						
						endif;  // bp_field_has_data()

								do_action( 'bp_profile_field_item' );

						endwhile; ?>
					
					</div><!-- end bp-profile-loop -->
			</div><!-- end bp-widget -->

			<?php do_action( 'bp_after_profile_field_content' ); ?>
		
		<?php else : // no fields
		
		// echo '<p>group:'.bp_get_the_profile_group_id() .' has no fields</p>';
		
		  endif; // bp_profile_group_has_fields() ?>
		
		<?php else : // in_array() 
		
			// echo '<p>exluded group id:'. echo bp_get_the_profile_group_id(); .'</p>';
		
		endif; // in_array() ?>

	<?php endwhile; ?>

	<?php do_action( 'bp_profile_field_buttons' ); ?>
	</div>
<?php endif; ?>

<?php do_action( 'bp_after_profile_loop_content' ); ?>
