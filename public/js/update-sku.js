

jQuery(document).ready(function($){
  function generateRandomSKU(n){
    let randomString = '';
    let characters = 'ABCDEFGHIJKLMNOPQRWSYZ1234567890';
    for(i = 0; i<n; i++){
      randomString += characters[Math.floor(Math.random()*characters.length)];
    }

    return randomString;
  }

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
                        randomString = generateRandomSKU(5);
                        let newSku = author + '-' + single_prodigi_id+ '-'+randomString + '-' + total_vendor_products;
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
                let randomString = generateRandomSKU(4)
                let newSku = author + '-' + single_prodigi_id + '-'+ randomString + '-' + total_vendor_products;
                skuField = $('[data-name="sku"]')
                // console.log(skuField)
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
              data_id: $("#printable_sku").val(),
              
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

              // console.log(product);
            
              adminField.val( single_prodigi_min_cost);
              var i = 0;                
                skuField = $('[data-name="sku"]')
              skuField.each(function (indexOfSKU, singleVariableSKU) { 
                indexOfSKU +=1
                let randomString = generateRandomSKU(4)
                let newSku = author + '-' + single_prodigi_id + '-'+randomString+'-' + total_vendor_products+'-'+ indexOfSKU;
                
                singleVariableSKU.value = newSku;
                
               
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
      

        // Add select 2 to field
        $('#_printable_sku_field').select2();
        $('#printable_sku').select2();



    })


