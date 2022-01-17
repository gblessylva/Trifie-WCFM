

jQuery(document).ready(function($){
    $(document).ready(function() {
        $('#_printable_sku_field').change(function(){
            
          $.ajax({
                    type: "post",
                    url: SkuAjax.ajaxurl,
                    data: {
                      action: "update_printable_sku",
                      data_id: $(this).val(),
                      
                    },
                    beforeSend: function() {
                        // $('#wcfm-main-content').append(
                        //     '<div id="loader" class="lds-dual-ring hidden overlay"></div>'
                        // );

                        $('#loader').removeClass('hidden')
                    },
                    success: function (response) {
                      const {data} = response;
                      const {product, total_vendor_products, author} = data;
                      const {single_prodigi_cost, single_prodigi_id, single_prodigi_min_cost } = product;
                        $('#_admin_min_price').val( single_prodigi_min_cost);
                        // $('#regular_price').val(single_prodigi_min_cost);
                        let newSku = author + '-' + single_prodigi_id + '-' + total_vendor_products;
                        $('#sku').val(newSku); 
                    },
                    complete: function(){
                        $('#loader').addClass('hidden')
                    },
                  });
                });
        });
    })


