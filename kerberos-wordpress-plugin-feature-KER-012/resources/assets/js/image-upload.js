jQuery( document ).ready( function( $ ) {
    $('body').on('click', '.upload_icon', function(e){
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
            $('#icon-url').val(attachment.url);
            $('#image-render1').remove();
          
            $('#icon-div').append('<img src="'+ attachment.url + '" style="width:140px;height:140px"">');
            
        })
        .open(); 
    });
} );