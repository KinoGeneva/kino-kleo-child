jQuery(document).ready(function($){	
   				
           (function ($) {
   						
   						// var ajaxurl = "<?php echo admin_url('admin-ajax.php'); ?>";
   						
               var $form = $('.pending-form'),
                   $item = $form.find( '.pending-candidate');
   
               // Trigger to make AJAX call to set state for ID
               // ( 1:accept, -1:reject )
               function setState(id, state) {
   
                   // item clicked
                   var $item = $('.pending-candidate[data-id="' + id + '"]'),
                   
                   // gather data
                       data = {
                           action: 'set_kino_state',
                           id:      id,
                           state:   state
                       };
   
                   // make AJAX POST call    
                   $.ajax({
                       type: 'POST',
                       url: kino_ajax_object.ajax_url,
                       data: data,
                       success: function (response) {
   
                           // look at the response
   
                           if (response.success) {
   
                             // update the UI to reflect the response
                             $item.attr ('data-state', state);
                             
                             if ( state == 'kabaret-accept' ) {  
                             	$item.detach();
                             } else if ( state == 'kabaret-reject' ) {
                             	$item.detach();
                             } else if ( state == 'platform-accept' ) {
                             	 $item.detach();
                             } else if ( state == 'platform-reject' ) {
                             	 $item.detach();
                             } else if ( state == 'kabaret-moyen' ) {
                             	 $item.detach();
                             } else if ( state == 'kabaret-bien' ) {
                             	 $item.detach();
                             } else if ( state == 'payment-25' ) {
                             	 location.reload(true);
                             } else if ( state == 'payment-100' ) {
                             	 location.reload(true);
                             } else if ( state == 'payment-reset' ) {
                             	 location.reload(true);
                             } else if ( state == 'repas-60' ) {
                             	 location.reload(true);
                             } else if ( state == 'repas-100' ) {
                             	 location.reload(true);
                             } else if ( state == 'repas-reset' ) {
                             	 location.reload(true);
                             	 // $("#table-container").load("/kino-admin/inscriptions/ #inscription-table");
                             }
   
                               // succcess data
                               console.log(response.data);
   
                           } else {
   
                               // no good
                               console.log(response);
                           }
                       }
                   });
               }
   
               // setup the items
               $item.each (function (inx, item){
   
                   var $item = jQuery(item),
                       $actionBtn = $item.find ('.admin-action');

                   // setup the button click handlers
                   
                   $actionBtn.on ('click', function(){
                       var id = $item.attr ('data-id');
                       var kinoaction = $(this).attr ('data-action');
                       // alert ('id='+id+' action='+kinoaction);
                       setState( id, kinoaction);
                   });
   
               });
   
           })(jQuery);
           
    });