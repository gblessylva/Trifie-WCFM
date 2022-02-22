(function( $ ) {
	'use strict';

     jQuery(document).ready(function($){

        var mediaUploader;
      
        $('#printable-image-selector').click(function(e) {
          e.preventDefault();
          // If the uploader object has already been created, reopen the dialog
            if (mediaUploader) {
            mediaUploader.open();
            return;
          }
          // Extend the wp.media object
          mediaUploader = wp.media.frames.file_frame = wp.media({
            title: 'Choose Image',
            button: {
            text: 'Choose Image'
          }, multiple: false });
      
          // When a file is selected, grab the URL and set it as the text field's value
          mediaUploader.on('select', function() {
            let attachment = mediaUploader.state().get('selection').first().toJSON();

            let fileInput = $('#_printable_image');
            
            $('#high-res').attr("src", attachment.url);
           
            $('#_printable_image').val(attachment.url);

           
            if( $('#_printable_image').val() != ''){
              // deleteBTN.remove()
            let  content  = " <span id='delete-printable-image' style='background: black; font-size: 16px; color:white; padding:5px 10px; cursor: pointer; position: absolute' class='wcfm-button' > REMOVE </span>"
          
            $('#printable-image-selector').after(content);
            
            let deleteBTN = $('#delete-printable-image')
            deleteBTN.click(function (e) { 
              e.preventDefault();
              fileInput.val('') ;
              $('#high-res').attr("src", '');
                deleteBTN.remove();
            });

          // .................
              
            }
          });
          // Open the uploader dialog
          mediaUploader.open();
    
        });
      
        let fileInput = $('#_printable_image');
        if(fileInput.val() != ''){
              // deleteBTN.remove()
            let  content  = " <span id='delete-printable-image' style='background: black; font-size: 16px; color:white; padding:5px 10px; cursor: pointer; position: absolute' class='wcfm-delete-btn' > REMOVE </span>"
          
            $('#printable-image-selector').after(content);
            let deleteBTN = $('#delete-printable-image')
            deleteBTN.click(function (e) { 
              e.preventDefault();
              fileInput.val('') ;
              $('#high-res').attr("src", '');
                deleteBTN.remove();
            });

        }
        
     

      });

      

    $(document).ready(function(){
        let skuinput= $('#sku');
        $('#_printable_sku').change(function(){
            let selectInput = $('#_printable_sku').val();
            skuinput.val(selectInput)
        })
    })

    // Compare Admin Price
    $(document).ready(function(){

      // Admin Price



    // regularPriceField.change( compareAdminPrice());
        let admin_price = $('#_admin_min_price');
        let regular_price = $('#regular_price');
        let sale_price = $('#sale_price'); 

        regular_price.change(function(){
          let admin_price_value = parseInt( admin_price.val() );
          let regular_price_value =  parseInt (regular_price.val());
          let sale_price_value = parseInt(sale_price.val());
          let submitBTN = $('.wcfm_submit_button');
            if (regular_price_value <= admin_price_value ){
              regular_price.addClass('is-invalid');
              let errorHolder = $('.regular_price');
              errorHolder.prepend('<span class="error-message">The Price must be greater than the Minimum Price. / 商品価格（Price）は最低価格（Min Product Price）より大きい数字にしてください</span>');
                regular_price.focus();
                submitBTN.hide();
                let errorClass = $('.error-message');
                if(errorClass.length > 1){
                    errorClass.last().remove();
                }
            }else if(sale_price_value <= admin_price_value){  
              sale_price.addClass('is-invalid');
              let errorHolder = $('.sale_price');
              errorHolder.prepend('<span class="error-message">The Price must be greater than the Minimum Price. / 商品価格（Price）は最低価格（Min Product Price）より大きい数字にしてください</span>');
                sale_price.focus();
                submitBTN.hide();
                let errorClass = $('.error-message');
                if(errorClass.length > 1){
                    errorClass.last().remove();
                }

            } else{
              $('.error-message').remove();
              submitBTN.show();
            } 
           
        });

    });



    // Function to toogle Public, Private and Passworded views
    $(document).ready(function(){

      let visibilitySdiv = $('#wcfm-post-visibility-select');
      let passwordSpan = $('#wcfm-password-span');
      // hide visibility div
      visibilitySdiv.hide();

      let passwordcheck = $('.password-check').is(":checked");
      if (passwordcheck){
        visibilitySdiv.show();
        passwordSpan.show();
        $('#wcfm-post-visibility-display').html('Password');

      }else{
        visibilitySdiv.hide();
        passwordSpan.hide();
        let passwordInput = $('#wfcm_post_password');
      // passwordSpan.hide();
      let status = $('.product-status').html();

      $('#wcfm-post-visibility-display').html(status);

      }

      
      $('.wcfm-product-edit-visibility').click(function(){

        visibilitySdiv.toggle();
        
      
      })
      // change contents of span
      
      let temlateCheckbox = $('#is_product_template');
      let templateRadio =  $('.visibility-radio-template');
      let publicRadio =  $('.visibility-radio-public')
      temlateCheckbox.click(function() {
        
        if($('#is_product_template').prop("checked") == true){
          $('#template_value').val('enabled')
          templateRadio.attr('checked', true)
          publicRadio.prop('checked', false)
         
        }else if($('#is_product_template').prop("checked") == false){
          publicRadio.prop('checked', true)
          templateRadio.prop('checked', false);
          $('#template_value').val('')

        }

       
    });
    // Auto set Template when template status is clicked
    templateRadio.click(function(){
      $('#is_product_template').prop("checked", true);
    })
    publicRadio.click(function () {
      $('#is_product_template').prop("checked", false);
      })
    if(templateRadio.prop('checked')==true){
      $('#is_product_template').prop("checked", true);
    }else if(templateRadio.prop('checked')==false){
      $('#is_product_template').prop("checked", false);
    }
  
    


      // Toogle password field
      $('input[type="radio"]').click(function(){
        let currentRadio = $(this).attr("value");
        // $(this).attr("value");
        $('#wcfm-post-visibility-display').html(currentRadio);
       
        // if(passwordInput.val() != ''){
        //   passwordInput.prop('checked', true);
        // }
        if(currentRadio == 'password' ){
          passwordSpan.show()
        }
        else{
          passwordSpan.hide()
        }
        if(currentRadio == 'template'){
          $('#is_product_template').prop("checked") == true
        }
        

         
      });



      //Save and replace value
      $('.wcfm-save-post-visibility').click(function(){
        visibilitySdiv.hide()
      })
      $('.wcfm-cancel-post-visibility').click(function(){
        visibilitySdiv.hide();
      })
    })

   
    // Check Template Value
      $('#product_type').change(function(){
        let productType = $('#product_type');
        if(productType.val()=='variable'){
          $('#_printable_sku_field').hide()
          $('._printable_sku_field').hide()
          $('._admin_min_price').hide()
          $('#_admin_min_price').hide()
        }
      })
    

   })( jQuery );

   
let variablePricingField = document.querySelectorAll('[data-name="regular_price"]');
let adminMinPrice = document.querySelectorAll('[data-name="_admin_min_price"]');
let sale_price = document.querySelectorAll('[data-name="sale_price"]');
let errorHolder = document.querySelectorAll('.regular_variation_price');


variablePricingField.forEach((field)=>{
  adminMinPrice.forEach((price)=>{
    field.addEventListener('change', (e)=>{
      if(parseInt(field.value) <= parseInt(price.value)){
        errorHolder.forEach((error)=>{
          error.innerHTML = "<span class='error-message'>The Price must be greater than the Minimum Price. / 商品価格（Price）は最低価格（Min Product Price）より大きい数字にしてください</span>";

        })
       


      }
 
    })
    
    
  })
  // Loop sales Price

  sale_price.forEach((sales)=>{

  })
  
})

