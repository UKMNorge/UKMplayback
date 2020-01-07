jQuery(document).ready(function(){
    jQuery('#fileupload_playback').fileupload({
        // Uncomment the following to send cross-domain cookies:
        xhrFields: {withCredentials: true},
        url: 'https://playback.'+ UKM_HOSTNAME +'/upload/jQupload_recieve.php',
        fileTypes: '^audio\/(.)+',
        autoUpload: true,
        formData: {'season': jQuery('#season').val(),
	        	   'pl_id': jQuery('#pl_id').val()
	        	   },
        progressall: function (e, data) {
	        var progress = parseInt(data.loaded / data.total * 100, 10);
	        jQuery('#uploadprogress').attr('value', progress);
	    },
    }).bind('fileuploaddone', function(e, data){
		    if(!data.result.success) {
			    fileUploadError( data.result );
		    } else {
			   	jQuery('#uploading').slideUp();
			   	jQuery('#uploaded').slideDown();
			    jQuery('#filename').val(data.result.files[0].file_id);
			    jQuery('#submitbutton').attr('disabled','').removeAttr('disabled');
			}
	}).bind('fileuploadstart', function(){
		jQuery('#filechooser').slideUp();
		jQuery('#uploading').slideDown();
		jQuery('#fileupload_dropzone').fadeOut();
	});
	
   if(jQuery('#fileupload_playback').html() !== 'undefined' && jQuery('#fileupload_playback').html() !== undefined) {
	    if (jQuery.support.cors) {
	        jQuery.ajax({
	            url: 'https://playback.' + UKM_HOSTNAME + '/upload/jQupload_cors.php',
	            type: 'HEAD'
	        }).fail(function () {
	        	var result = {'success': false,
		        			  'message': 'Beklager, playbackserveren er ikke tilgjengelig akkurat nå'};
	            fileUploadError( result );
	        });
	    }
	}
});


jQuery(document).on('click', '.PBdel', function() {
	return confirm('Er du sikker på at du vil slette denne?');
});

////////////////////////////////////////////////////////////////////////////////////////
// FILEUPLOAD: HJELPERE
////////////////////////////////////////////////////////////////////////////////////////
	function fileUploadError(result) {
		console.error(result);
		var hbt_lastopp_error = Handlebars.compile( jQuery('#handlebars-lastopp-error').html() );
		
		jQuery('#fileupload_container').slideUp();
		
		jQuery('#fileupload_message').html( hbt_lastopp_error( result )).slideDown();
		
	}
