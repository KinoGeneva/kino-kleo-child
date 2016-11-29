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

});
</script>

<?php

?>