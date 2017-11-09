<?php 

?>
<script src="<?php echo get_stylesheet_directory_uri(); ?>/js/lib/form-validator/jquery.form-validator.min.js"></script>
<script>

// info: https://github.com/victorjonsson/jQuery-Form-Validator/wiki

jQuery(document).ready(function($){

  $.validate({
  	form : '#profile-edit-form',
  	lang : 'fr',
  	language : {
  	                requiredFields: 'Veuillez remplir ce champ'
  	            },
  	errorMessagePosition : 'element',
  	scrollToTopOnError : true,
    modules : 'location, file',
    onModulesLoaded : function() {
      $('#country').suggestCountry();
    },
    onError : function($form) {
      alert('Veuillez remplir tous les champs obligatoires avant de soumettre le formulaire!');
      mixpanel.track( "Form Error" );
    },
    onSuccess : function(form) {
      mixpanel.track( "Submitted Form" );
    }
  });
  
  // $('#profile-edit-form').validateOnBlur();

  // Restrict presentation length


	//onglet profil kinoïte, #138: alerte si kino 2018 n'est pas coché
	$('#field_2060').on('beforeValidation', function(value, lang, config) {
		if($("#check_acc_field_2060").is(":checked")) {
			//
		}
		else {
			alert("Vous n'avez pas coché la case \"Je m‘inscris au Kino Kabaret 2018\".");
		}
	});
});
</script>

<?php

?>
