jQuery(document).ready(function() {
    jQuery('#fileupload_playback').fileupload({
        // Uncomment the following to send cross-domain cookies:
        xhrFields: { withCredentials: true },
        url: 'https://playback.' + UKM_HOSTNAME + '/upload/jQupload_recieve.php',
        fileTypes: '^audio\/(.)+',
        autoUpload: true,
        formData: {
            'season': jQuery('#season').val(),
            'pl_id': jQuery('#pl_id').val()
        },
        progressall: function(e, data) {
            var progress = parseInt(data.loaded / data.total * 100, 10);
            jQuery('#uploadprogress').attr('value', progress);
        },
    }).bind('fileuploaddone', function(e, data) {
        if (!data.result.success) {
            fileUploadError(data.result);
        } else {
            jQuery('#uploading').slideUp();
            jQuery('#uploaded').slideDown();
            jQuery('#filename').val(data.result.files[0].file_id);
            jQuery('#submitbutton').attr('disabled', '').removeAttr('disabled');
        }
    }).bind('fileuploadstart', function() {
        jQuery('#filechooser').slideUp();
        jQuery('#uploading').slideDown();
        jQuery('#fileupload_dropzone').fadeOut();
    });

    if (jQuery('#fileupload_playback').html() !== 'undefined' && jQuery('#fileupload_playback').html() !== undefined) {
        if (jQuery.support.cors) {
            jQuery.ajax({
                url: 'https://playback.' + UKM_HOSTNAME + '/upload/jQupload_cors.php',
                type: 'HEAD'
            }).fail(function() {
                var result = {
                    'success': false,
                    'message': 'Beklager, playbackserveren er ikke tilgjengelig akkurat nå'
                };
                fileUploadError(result);
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
    jQuery('#fileupload_container').slideUp();
    jQuery('#fileupload_message').html(twigJS_lastopperror.render(result)).slideDown();
}

///// LAST NED ZIP
var workList = new UKMresources.workQueue(
    'zipList', {
        /*filterCountData: function(data) {
            console.log(data);
            return data.action;
        },*/
        elementHandler: function(zip_id) {
            var emitter = new UKMresources.emitter('zip_' + zip_id);
            var selector = '#zip_' + zip_id;
            var item = jQuery(selector);

            console.group('TODO: ' + zip_id);
            console.log(item);
            console.log(item.data('filecount'));

            var response = {
                id: zip_id,
                filecount: item.data('filecount')
            };

            // Har ingen filer
            if (response.filecount == 0) {
                setTimeout(
                    function() {
                        emitter.emit('success', response);
                    }, 100
                );
            } else {
                UKMresources.Request({
                    url: 'https://playback.' + window.location.hostname + '/zipMePlease/',
                    containers: {
                        loading: selector + ' .loading',
                        success: selector + ' .success',
                        error: selector + ' .error',
                        fatalError: '#status',
                        main: '#formContainer'
                    },
                    handleSuccess: (response) => {
                        emitter.emit('success', response);
                    },
                    handleError: (response, message) => {
                        console.log('handleError');
                        console.log(response);
                        console.log(message);
                        emitter.emit('error', response);
                    }
                }).do({
                    data: item.data('package'),
                    id: zip_id
                });

            }

            console.groupEnd();
            return emitter;
        }
    }
);

// Når alle filer er zip'et
workList.on('done', () => {
    jQuery('#pleasewait').slideUp();
});

// En fil er ferdig
workList.on('success', (data) => {
    // Ingen filer betyr at vi ikke får serverdata,
    // men bare enkel ID-data tilbake
    if (data.filecount == 0) {
        jQuery('#zip_' + data.id)
            //.append('Ingen innslag har mediefiler')
            .appendTo('#cleanedEmptyList');
        return true;
    }

    // Dobbeltsjekk at vi har serverdata
    if (data.POST) {
        var item = jQuery('#zip_' + data.POST.id);

        item.find('.download').attr('href', data.url).fadeIn();
        item.find('.navn.preLoad').slideUp();
        item.find('.navn.postLoad').slideDown();

        if (data.hasErrors) {
            data.errors.forEach(function(value) {
                console.log(value);
                if (value.type == 'fil') {
                    item.find('.warnings > ol').append(twigJS_lastnederror.render(value));
                } else {
                    item.find('.warnings > ol').append(
                        jQuery('<li class="list-group-item alert-danger">')
                        .html('<b>Klarte ikke å lagre zip-filen: </b>' + value)
                    );
                    item.find('.download').hide();
                }
            });
            item.find('.warnings').slideDown();
        }
        return true;
    }

    return false;
});


workList.on('error', (data) => {
    console.log(data);
    console.error('h');
    var item = jQuery('#zip_' + data.id);

    item.append('<br />' + data.message);

    item
        .addClass('alert-danger')
        .appendTo('#cleanedList');
});


jQuery(document).ready(function() {
    console.log('Start ziplist');
    jQuery('#zipList li').each((index, zipfile) => {
        console.log(index);
        console.log(zipfile);
        console.log(jQuery(zipfile).data('id'));
        workList.push(jQuery(zipfile).data('id'));
    });
    workList.start();
});