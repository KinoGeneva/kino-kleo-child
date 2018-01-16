//https://codex.wordpress.org/Javascript_Reference/wp.media
jQuery(function($){
	
	// Set all variables to be used in scope
	var frame,
	imgContainer = $('#new_img_container');
	addImgLink = $('#img_upload'),

	// ADD IMAGE LINK
	addImgLink.on( 'click', function( event ){
		event.preventDefault();

		// If the media frame already exists, reopen it.
		if ( frame ) {
			frame.open();
			return;
		}

		// Create a new media frame
		frame = wp.media({
			title: jQuery( this ).data( 'uploader_title' ),
			multiple: 'add',
			library: {
				type: 'image'
			},
		});    

		//récupérer la sélection faite
		frame.on( 'select', function() {
			// récupérer les détails des images
			var selection = frame.state().get('selection');
			//chaque image
			
			selection.map( function( attachment ) {
				attachment = attachment.toJSON();
				
				//test sur l'extension
				if(attachment.filename.indexOf('jpg') >=0 || attachment.filename.indexOf('JPG') >=0 || attachment.filename.indexOf('jpeg') >=0 || attachment.filename.indexOf('JPEG') >=0 ){
				
					//ajoute un champ avec l'id et un autre avec un aperçu
					imgContainer.append( '<input type="hidden" name="img['+ attachment.id +'][title]" value="'+ attachment.title +'" >' ); //post_title
					imgContainer.append( '<input type="hidden" name="img['+ attachment.id +'][filename]" value="'+ attachment.filename +'" >' );
					imgContainer.append( '<input type="hidden" name="img['+ attachment.id +'][url]" value="'+ attachment.url +'" >' ); //guid
					imgContainer.append( '<input type="hidden" name="img['+ attachment.id +'][link]" value="'+ attachment.link +'" >' ); //la page du fichier
					imgContainer.append( '<input type="hidden" name="img['+ attachment.id +'][name]" value="'+ attachment.name +'" >' ); //post_name
					imgContainer.append( '<img src="'+ attachment.url +'" alt="'+ attachment.title +'" class="alignleft size-thumbnail wp-image-'+ attachment.id +'" style="width:150px;"/>' );
				}

				 //console.log(attachment);
				 //Object { id: 5447, title: "salon test kino", filename: "salon-test-kino-2.jpg", url: "http://localhost/web/wp-content/uploads/2018/01/salon-test-kino-2.jpg", link: "http://localhost/web/salon-test-kino-3/", alt: "", author: "949", description: "", caption: "", name: "salon-test-kino-3", … }
				
			});
		});

		// Finally, open the modal on click
		frame.open();
	});

});
