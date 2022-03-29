jQuery(document).ready(function($){

    var dt = $('#product-templates').DataTable();

    $(document).on('click', '.wcfm_product_delete', function(e){
        e.preventDefault();
        let id = $(this).data('proid');
        let nonce = $(this).data('nonce');
        // console.log('id', id);
        // console.log('nonce', nonce);
        $.ajax({
            type: 'post',
            url: DeleteAjax.ajaxurl,
            data : {
                action: 'delete_product_template',
                id: id,
                nonce: nonce,

            }

        }).success(function(result){
            console.log(result);
            // console.log(dt)
            dt.ajax.reload(null, false);

        })
        
        
    })
})


