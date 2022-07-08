
jQuery(function($){
    
    function re_calculate(){
        var sum = 0.00;
            
        $(".total").each(function(){
             sum += +$(this).text();
        });
            $("#bundle_regular_price").val(sum.toFixed(2));
            $("#regular_price").text(sum.toFixed(2));

        }
    function item_total(){
        
        $('.qty').change(function () {
            var parent = $(this).closest('tr');
            $total=parseFloat(parent.find('.qty').val()) * parseFloat(parent.find('.item_price').val());
            parent.find('.total').text($total.toFixed(2));
            re_calculate();
        });
    }   
    function remove_row(){
        $(".myRemoveButton").click(function() {  
            $(this).closest('.item-row').remove();
            re_calculate();
              
           });
    }
        
     $('body').on('change', '#items', function(e){
        
        var id=$("#items").val();
       
        var data = {
          'action': 'my_action',
          'post_id': id,   
       };
    
       jQuery.post(ajax_object.ajax_url, data, function(response) {
            
            $("#bundle-table").append(response);
            item_total();
            re_calculate();
            remove_row();
            
       });
         
    });
    
    remove_row();

    item_total();

  
});
    