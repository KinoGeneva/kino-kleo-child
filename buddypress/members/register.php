<div id="buddypress" class="kino-register">

	<?php do_action( 'bp_before_register_page' ); ?>

	<div class="page" id="register-page">
	<?php the_content();?>

		<form action="" name="signup_form" id="signup_form" class="standard-form" method="post" enctype="multipart/form-data">

		<?php if ( 'registration-disabled' == bp_get_current_signup_step() ) : ?>
			<?php do_action( 'template_notices' ); ?>
			<?php do_action( 'bp_before_registration_disabled' ); ?>

				<p><?php _e( 'User registration is currently not allowed.', 'buddypress' ); ?></p>

			<?php do_action( 'bp_after_registration_disabled' ); ?>
		<?php endif; // registration-disabled signup setp ?>

		<?php if ( 'request-details' == bp_get_current_signup_step() ) : ?>

			<?php do_action( 'template_notices' ); ?>

			
			<?php // show content of "inscription" page:
			
			$kino_query_args = array( 
						'posts_per_page' => 1,
						'post_type' => 'page',
						'pagename' => 'inscription-membre',
				 );
			
			$kino_query = new WP_Query( $kino_query_args );
				
				if ($kino_query->have_posts()) : 
				while( $kino_query->have_posts() ) : $kino_query->the_post();
									
									the_content();
									
				endwhile; 
				endif;
				wp_reset_postdata();			
			
			 ?>
			
				<div class="row">
					
					<div class="col-sm-6">
			
						<?php do_action( 'bp_before_account_details_fields' ); ?>

						<div class="register-section" id="basic-details-section">

						<?php /***** Basic Account Details ******/ ?>

						<h4><?php _e( 'Account Details', 'buddypress' ); ?></h4>
						
						<div class="signup-username-group">
						<label for="signup_username"><?php _e( 'Username', 'buddypress' ); ?> <?php _e( '(required)', 'buddypress' ); ?></label>
						<!-- <p>Pas d'espace, ni de caractères spéciaux, SVP !</p> -->
						<?php do_action( 'bp_signup_username_errors' ); ?>
						<input type="text" name="signup_username" id="signup_username" value="<?php bp_signup_username_value(); ?>" />
						<div id="signup_username_error"></div>
						</div>

						<label for="signup_email"><?php _e( 'Email Address', 'buddypress' ); ?> <?php _e( '(required)', 'buddypress' ); ?></label>
						<?php do_action( 'bp_signup_email_errors' ); ?>
						<input type="text" name="signup_email" id="signup_email" value="<?php bp_signup_email_value(); ?>" />

						<label for="signup_password"><?php _e( 'Choose a Password', 'buddypress' ); ?> <?php _e( '(required)', 'buddypress' ); ?></label>
						<?php do_action( 'bp_signup_password_errors' ); ?>
						<input type="password" name="signup_password" id="signup_password" value="" class="password-entry" />
                        <div id="pass-strength-result"></div>

						<label for="signup_password_confirm"><?php _e( 'Confirm Password', 'buddypress' ); ?> <?php _e( '(required)', 'buddypress' ); ?></label>
						<?php do_action( 'bp_signup_password_confirm_errors' ); ?>
						<input type="password" name="signup_password_confirm" id="signup_password_confirm" value="" class="password-entry-confirm" />

						<?php do_action( 'bp_account_details_fields' ); ?>

					</div><!-- #basic-details-section -->

					<?php do_action( 'bp_after_account_details_fields' ); ?>
				</div>
				<div class="col-sm-6">

					<?php /***** Extra Profile Details ******/ ?>

					<?php if ( bp_is_active( 'xprofile' ) ) : ?>

						<?php do_action( 'bp_before_signup_profile_fields' ); ?>

						<div class="register-section" id="profile-details-section">

							<h4><?php _e( 'Profile Details', 'buddypress' ); ?></h4>

							<?php /* Use the profile field loop to render input fields for the 'base' profile field group */ ?>
							<?php if ( bp_is_active( 'xprofile' ) ) : if ( bp_has_profile( array( 'profile_group_id' => 1, 'fetch_field_data' => false ) ) ) : while ( bp_profile_groups() ) : bp_the_profile_group(); ?>

							<?php while ( bp_profile_fields() ) : bp_the_profile_field(); ?>

                                <div<?php bp_field_css_class( 'editfield' ); ?>>

									<?php
									$field_type = bp_xprofile_create_field_type( bp_get_the_profile_field_type() );
									$field_type->edit_field_html();

									do_action( 'bp_custom_profile_edit_fields_pre_visibility' );

									if ( bp_current_user_can( 'bp_xprofile_change_field_visibility' ) ) : ?>
										<p class="field-visibility-settings-toggle" id="field-visibility-settings-toggle-<?php bp_the_profile_field_id() ?>">
											<?php printf( __( 'This field can be seen by: <span class="current-visibility-level">%s</span>', 'buddypress' ), bp_get_the_profile_field_visibility_level_label() ) ?> <a href="#" class="visibility-toggle-link"><?php _ex( 'Change', 'Change profile field visibility level', 'buddypress' ); ?></a>
										</p>

										<div class="field-visibility-settings" id="field-visibility-settings-<?php bp_the_profile_field_id() ?>">
											<fieldset>
												<legend><?php _e( 'Who can see this field?', 'buddypress' ) ?></legend>

												<?php bp_profile_visibility_radio_buttons() ?>

											</fieldset>
											<a class="field-visibility-settings-close" href="#"><?php _e( 'Close', 'buddypress' ) ?></a>

										</div>
									<?php else : ?>
										<p class="field-visibility-settings-notoggle" id="field-visibility-settings-toggle-<?php bp_the_profile_field_id() ?>">
											<?php printf( __( 'This field can be seen by: <span class="current-visibility-level">%s</span>', 'buddypress' ), bp_get_the_profile_field_visibility_level_label() ) ?>
										</p>
									<?php endif ?>

									<?php do_action( 'bp_custom_profile_edit_fields' ); ?>

									<p class="description"><?php bp_the_profile_field_description(); ?></p>

								</div>

							<?php endwhile; ?>

							<input type="hidden" name="signup_profile_field_ids" id="signup_profile_field_ids" value="<?php bp_the_profile_group_field_ids(); ?>" />

							<?php endwhile; endif; endif; ?>

							<?php do_action( 'bp_signup_profile_fields' ); ?>

						</div><!-- #profile-details-section -->

						<?php do_action( 'bp_after_signup_profile_fields' ); ?>

					<?php endif; ?>
				
				</div>
			</div>

			<?php if ( bp_get_blog_signup_allowed() ) : ?>

				<?php do_action( 'bp_before_blog_details_fields' ); ?>

				<?php /***** Blog Creation Details ******/ ?>

				<div class="register-section" id="blog-details-section">

					<h4><?php _e( 'Blog Details', 'buddypress' ); ?></h4>

					<p><input type="checkbox" name="signup_with_blog" id="signup_with_blog" value="1"<?php if ( (int) bp_get_signup_with_blog_value() ) : ?> checked="checked"<?php endif; ?> /> <?php _e( 'Yes, I\'d like to create a new site', 'buddypress' ); ?></p>

					<div id="blog-details"<?php if ( (int) bp_get_signup_with_blog_value() ) : ?>class="show"<?php endif; ?>>

						<label for="signup_blog_url"><?php _e( 'Blog URL', 'buddypress' ); ?> <?php _e( '(required)', 'buddypress' ); ?></label>
						<?php do_action( 'bp_signup_blog_url_errors' ); ?>

						<?php if ( is_subdomain_install() ) : ?>
							http:// <input type="text" name="signup_blog_url" id="signup_blog_url" value="<?php bp_signup_blog_url_value(); ?>" /> .<?php bp_blogs_subdomain_base(); ?>
						<?php else : ?>
							<?php echo home_url( '/' ); ?> <input type="text" name="signup_blog_url" id="signup_blog_url" value="<?php bp_signup_blog_url_value(); ?>" />
						<?php endif; ?>

						<label for="signup_blog_title"><?php _e( 'Site Title', 'buddypress' ); ?> <?php _e( '(required)', 'buddypress' ); ?></label>
						<?php do_action( 'bp_signup_blog_title_errors' ); ?>
						<input type="text" name="signup_blog_title" id="signup_blog_title" value="<?php bp_signup_blog_title_value(); ?>" />

						<span class="label"><?php _e( 'I would like my site to appear in search engines, and in public listings around this network.', 'buddypress' ); ?>:</span>
						<?php do_action( 'bp_signup_blog_privacy_errors' ); ?>

						<label><input type="radio" name="signup_blog_privacy" id="signup_blog_privacy_public" value="public"<?php if ( 'public' == bp_get_signup_blog_privacy_value() || !bp_get_signup_blog_privacy_value() ) : ?> checked="checked"<?php endif; ?> /> <?php _e( 'Yes', 'buddypress' ); ?></label>
						<label><input type="radio" name="signup_blog_privacy" id="signup_blog_privacy_private" value="private"<?php if ( 'private' == bp_get_signup_blog_privacy_value() ) : ?> checked="checked"<?php endif; ?> /> <?php _e( 'No', 'buddypress' ); ?></label>

						<?php do_action( 'bp_blog_details_fields' ); ?>

					</div>

				</div><!-- #blog-details-section -->

				<?php do_action( 'bp_after_blog_details_fields' ); ?>

			<?php endif; ?>

			<?php do_action( 'bp_before_registration_submit_buttons' ); ?>

			<div class="submit">
				<input type="submit" name="signup_submit" id="signup_submit" value="<?php esc_attr_e( 'Complete Sign Up', 'buddypress' ); ?>" />
			</div>

			<?php do_action( 'bp_after_registration_submit_buttons' ); ?>

			<?php wp_nonce_field( 'bp_new_signup' ); ?>

		<?php endif; // request-details signup step ?>

		<?php if ( 'completed-confirmation' == bp_get_current_signup_step() ) : ?>

			<?php do_action( 'template_notices' ); ?>
			<?php do_action( 'bp_before_registration_confirmed' ); ?>

			<?php if ( bp_registration_needs_activation() ) : ?>
				<p><?php _e( 'You have successfully created your account! To begin using this site you will need to activate your account via the email we have just sent to your address.', 'buddypress' ); ?></p>
			<?php else : ?>
				<p><?php _e( 'You have successfully created your account! Please log in using the username and password you have just created.', 'buddypress' ); ?></p>
			<?php endif; ?>

			<?php do_action( 'bp_after_registration_confirmed' ); ?>

		<?php endif; // completed-confirmation signup step ?>

		<?php do_action( 'bp_custom_signup_steps' ); ?>

		</form>

	</div>
	
	<script>
	jQuery(document).ready(function($){	
		
	  	// add target_blank to .kino-edit-profile 
	  	$("#signup_form p.description a[href^=http]").attr('target', '_blank');
	  	
	  	// hide some fields
//	  	$("#signup_form .field_927").hide();
//	  	$("#signup_form .field_1079").hide();
//	  	$("#signup_form .field_1075").hide();
	  	
	  	$("#signup_form .field-visibility-settings-toggle").hide();
	  	
	  	// https://bitbucket.org/ms-studio/kinogeneva/issues/69/identifiant
	  	// Il semble que le champs “identifiant” accepte un espace lors de la saisie (à l’inscription) mais pas quand on se log de nouveau... Y a-t-il une façon d’éviter la saisie d’espace?
	  	
	  	$("input#signup_username").attr({
	  		    'data-validation':'alphanumeric',  // required - cf http://formvalidator.net/#file-validators
	  		    'data-validation-allowing':'-_',
	  		    'data-validation-error-msg':'Vous ne pouvez utiliser que des caractères alphanumériques, des chiffres et -_',
	  		});;
	  	
	});
	</script>
	<script src="<?php echo get_stylesheet_directory_uri(); ?>/js/lib/form-validator/jquery.form-validator.min.js"></script>
	<script>
	
	// info: https://github.com/victorjonsson/jQuery-Form-Validator/wiki
	
	jQuery(document).ready(function($){
	
		var $messages = $('#signup_username_error');
	
	  $.validate({
	  	form : '#signup_form',
	  	lang : 'fr',
	  	language : {
	  	                requiredFields: 'Veuillez remplir ce champ'
	  	            },
	  	validateOnBlur : true,
	  	errorMessagePosition : $messages,
	  	scrollToTopOnError : false,
	    onError : function($form) {
	      alert('Veuillez remplir tous les champs obligatoires avant de soumettre le formulaire!');
	      // mixpanel.track( "Signup Form Error" );
	    },
	    onSuccess : function(form) {
	      // mixpanel.track( "Submitted Signup Form" );
	    }
	  });
	  
	  // $('#profile-edit-form').validateOnBlur();
	
	  // Restrict presentation length
	
	});
	</script>

	<?php do_action( 'bp_after_register_page' ); ?>

</div><!-- #buddypress -->
