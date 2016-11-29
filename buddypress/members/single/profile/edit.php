<?php do_action( 'bp_before_profile_edit_content' );

if ( bp_has_profile( 'profile_group_id=' . bp_get_current_profile_group_id() ) ) :
	while ( bp_profile_groups() ) : bp_the_profile_group(); ?>
	<div id="checkout-widget-area">
   		<?php dynamic_sidebar( 'Tab Widget Area' ); ?>
	</div>

<form action="<?php kino_the_profile_group_edit_form_action(); ?>" method="post" id="profile-edit-form" class="standard-form <?php bp_the_profile_group_slug(); ?>">

	<?php do_action( 'bp_before_profile_field_content' ); ?>
	
		<div class="hr-title hr-full hr-double"><abbr><?php printf( __( "Modification de votre profil", "buddypress" ), bp_get_the_profile_group_name() ); ?></abbr></div>
		<div class="gap-10"></div>
		
		<ul id="button-nav" class="button-nav clearfix">
			
			<?php 
			
//			If Admin, Show Test Area
			
			if( current_user_can('administrator')) {  
				
				bp_get_template_part( 'members/single/profile/edit-testing' );
				
			}
			
			
			bp_profile_group_tabs(); 
			
			?>

		</ul>

		<div class="clear"></div>
		
		<div class="kino-edit-profile clearfix">
		
		<style>
		/* apply immediately to hide items */
		#buddypress .field-visibility-settings {
		    /* display: none; */
		}
		</style>

		<?php while ( bp_profile_fields() ) : bp_the_profile_field(); ?>

			<div<?php bp_field_css_class( 'editfield' ); ?>>

				<?php
				$field_type = bp_xprofile_create_field_type( bp_get_the_profile_field_type() );
				$field_type->edit_field_html();
				
				

				do_action( 'bp_custom_profile_edit_fields_pre_visibility' );
				?>

				<?php
				
				// if( current_user_can('administrator')) { 
					// show everywhere for admins?
					// bp_get_template_part( 'members/single/profile/edit-visibility' );
				
				// }
				
				// is visibility setting allowed for this field ?
				
				$field_id = bp_get_the_profile_field_id();
				
				if ( 'allowed' == bp_xprofile_get_meta( $field_id, 'field', 'allow_custom_visibility' ) ) {
				
				  bp_get_template_part( 'members/single/profile/edit-visibility' );
				
				}
				
				  ?>

				<?php do_action( 'bp_custom_profile_edit_fields' ); ?>

				<p class="description"><?php bp_the_profile_field_description(); ?></p>
			</div>

		<?php endwhile; ?>
		
		</div>

	<?php do_action( 'bp_after_profile_field_content' ); ?>
	
 	<?php 
 	$currenttab = bp_get_current_profile_group_id();
 		
 		if($currenttab === '1') {
 				 dynamic_sidebar( 'Tab Bottom Profil Kinoite' );
 			}
 		if($currenttab === '5') {
 			 dynamic_sidebar( 'Tab Bottom Director Widget Area' );
 		}
 		elseif($currenttab === '6') {
 			 dynamic_sidebar( 'Tab Bottom Comedian Widget Area' );
 		}
 		elseif($currenttab === '10') {
 			 dynamic_sidebar( 'Tab Bottom Identity Widget Area' );
 		}
 		elseif($currenttab === '7') {
 			 dynamic_sidebar( 'Tab Bottom Technician Widget Area' );
 		}
 		elseif($currenttab === '12') {
 			 dynamic_sidebar( 'Tab Bottom Kabaret 1 Widget Area' );
 		}
 		elseif($currenttab === '9') {
 			 dynamic_sidebar( 'Tab Bottom Kabaret 2 Widget Area' );
 		}
 		else {  dynamic_sidebar( 'Tab Bottom Widget Area' ); 
 		} 

 	?>
	

	<div class="submit clearfix">
		<input type="submit" name="profile-group-edit-submit" id="profile-group-edit-submit" value="<?php esc_attr_e( 'Save Changes', 'buddypress' ); ?> " />
	</div>

	<input type="hidden" name="field_ids" id="field_ids" value="<?php bp_the_profile_group_field_ids(); ?>" />

	<?php wp_nonce_field( 'bp_xprofile_edit' ); ?>

</form>

<?php // bp_get_template_part( 'members/single/profile/edit-jquery' ); ?>
<?php bp_get_template_part( 'members/single/profile/edit-javascript' ); ?>

<?php bp_get_template_part( 'members/single/profile/edit-validator' ); ?>

<?php endwhile; endif; ?>

<?php do_action( 'bp_after_profile_edit_content' ); ?>