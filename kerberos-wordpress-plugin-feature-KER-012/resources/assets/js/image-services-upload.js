jQuery( document ).ready( function( $ ) {
    $('body').on('click', '.upload_service_image', function(e){
        e.preventDefault();
      
        aw_uploader = wp.media({
            title: 'Upload Icon',
            button: {
                text: 'Use this image',
              
            },
            type : 'image',
            multiple: false
        }).on('select', function() {
            var attachment = aw_uploader.state().get('selection').first().toJSON();
            $('#service-image-url').val(attachment.url);
            $('#service-image-render').empty();
            $('#service-image-render').append('<img src="'+ attachment.url + '" style="width:140px;height:140px"">');
           
        })
        .open();
       
    });

} );