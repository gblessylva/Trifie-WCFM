(function( $ ) {
	'use strict';

	/**
	 * All of the code for your public-facing JavaScript source
	 * should reside in this file.
	 *
	 * Note: It has been assumed you will write jQuery code here, so the
	 * $ function reference has been prepared for usage within the scope
	 * of this function.
	 *
	 * This enables you to define handlers, for when the DOM is ready:
	 *
	 * $(function() {
	 *
	 * });
	 *
	 * When the window is loaded:
	 *
	 * $( window ).load(function() {
	 *
	 * });
	 *
	 * ...and/or other possibilities.
	 *
	 * Ideally, it is not considered best practise to attach more than a
	 * single DOM-ready or window-load handler for a particular page.
	 * Although scripts in the WordPress core, Plugins and Themes may be
	 * practising this, we should strive to set a better example in our own work.
	 */

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
            // console.log(attachment.filename);

            let fileInput = $('#_printable_image');
            // console.log(fileInput);
            $('#high-res').attr("src", attachment.url);
            console.log($('#high-res'));
            $('#_printable_image').val(attachment.url);
          });
          // Open the uploader dialog
          mediaUploader.open();
        });
      
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



// // Update SKU
//     $(document).ready(function(){
//         let skuinput= $('#sku');
//         $('#_printable_sku').change(function(){
//             let selectInput = $('#_printable_sku').val();
//             skuinput.val(selectInput)
//         })
//     });



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
          templateRadio.attr('checked', true)
          publicRadio.prop('checked', false)
        }else if($('#is_product_template').prop("checked") == false){
          publicRadio.prop('checked', true)
          templateRadio.prop('checked', false);

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

    

   })( jQuery );

   
