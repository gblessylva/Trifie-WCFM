

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
                        let checkbox = $('#is_product_template').is(':checked');

                        if(checkbox){
                          $('#sku').val("");
                        }else{
                          $('#sku').val(newSku);
                        }
                         
                    },
                    complete: function(){
                        $('#loader').addClass('hidden')
                    },
                  });
                });

        }



        



        );

        $('#printable_sku').change(function(){


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
              let adminField = $('.admin_min_price')
            
              adminField.val( single_prodigi_min_cost);
                // $('#regular_price').val(single_prodigi_min_cost);
                let newSku = author + '-' + single_prodigi_id + '-' + total_vendor_products;
                skuField = $('[data-name="sku"]')
                console.log(skuField)
                skuField.val(newSku); 
            },
            complete: function(){
                $('#loader').addClass('hidden')
            },
          });

        })
        
        
      

        $('#update_printable_sku').click(function(){


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
              let adminField = $('.admin_min_price')
            
              adminField.val( single_prodigi_min_cost);
              var i = 0;                
                skuField = $('[data-name="sku"]')
              skuField.each(function (indexOfSKU, singleVariableSKU) { 
                indexOfSKU +=1
                let newSku = author + '-' + single_prodigi_id + '-' + total_vendor_products +'-'+ indexOfSKU;
                singleVariableSKU.value = newSku;
                // console.log(singleVariableSKU.value);
              });
            },
            complete: function(){
                $('#loader').addClass('hidden')
            },
          });

        })

  
        $('#is_product_template').click(function() {
          if($('#is_product_template').prop("checked") == true){
            
            
          }
        
        })
      

    })


