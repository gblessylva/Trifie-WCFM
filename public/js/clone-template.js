jQuery(document).ready(function($){


    $(document).on('click', '.clone-template', function(){
        let id = $(this).data('id');
        let nonce = $(this).data('nonce');
        $.ajax({
            type: 'post',
            url: CloneAjax.ajaxurl,
            data : {
                action: 'clone_prodigi_template',
                id: id, 
                nonce: nonce
            }, 
            beforeSend: function() {
                $('#loader').removeClass('hidden')
            },
            
            success : function (result){
                data = JSON.parse(result)
                const {product_id, success, homeurl, message} = data;
                if(success){
                    // console.log(product_id)
                    window.open(homeurl+'/store-manager/products-manage/'+product_id, '_blank');
                }
                
            },
            complete: function(){
                  $('#loader').addClass('hidden')
              },
        })
        
        return false;

    } )

   
})

