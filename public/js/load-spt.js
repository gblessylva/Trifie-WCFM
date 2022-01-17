jQuery( document ).ready( function( $ ) {

    function generateHTMLAttributeForm(){
        let attributesDiv = $('#attributes');
        attributesDiv.css({'height': '250px', 'overflow-y': 'scroll'});

       let  html = '<div class="multi_input_block ui-sortable-handle" style=""> <span> <span class="fields_collapser attributes_collapser wcfmfa fa-arrow-circle-down" title="Toggle Block"></span> <div class="content-seperator">'
            html += '<input type="hidden" id="attributes_term_name_10" name="attributes[10][term_name]" class=" multi_input_block_element" value="" data-name="term_name">' 
            html += '<p class="attributes_is_active_0 wcfm_title wcfm_ele simple variable external grouped booking checkbox_title"><strong>Active?</strong></p><label class="screen-reader-text" for="attributes_is_active_0">Active?</label><input type="checkbox" id="attributes_is_active_10" name="attributes[10][is_active]" class="wcfm-checkbox wcfm_ele attribute_ele simple variable external grouped booking multi_input_block_element" value="enable" data-name="is_active" checked="checked">'
            html += '<p class="attributes_name_0 wcfm_title wcfm_ele simple variable external grouped booking"><strong>Name</strong></p><label class="screen-reader-text" for="attributes_name_0">Name</label><input type="text" id="attributes_name_10" name="attributes[10][name]" class="wcfm-text wcfm_ele attribute_ele simple variable external grouped booking multi_input_block_element" value="" placeholder="" data-name="name">'
            html +='<p class="attributes_value_0 wcfm_title wcfm_ele simple variable external grouped booking"><strong>Value(s):</strong></p><label class="screen-reader-text" for="attributes_value_0">Value(s):</label><textarea id="attributes_value_10" name="attributes[10][value]" class="wcfm-textarea wcfm_ele simple variable external grouped booking multi_input_block_element" placeholder="Enter some text, some attributes by &quot;|&quot; separating values." rows="2" cols="20" data-name="value"></textarea>'
            html += '<p class="attributes_is_visible_0 wcfm_title wcfm_ele simple variable external grouped booking checkbox_title"><strong>Visible on the product page</strong></p><label class="screen-reader-text" for="attributes_is_visible_0">Visible on the product page</label><input type="checkbox" id="attributes_is_visible_10" name="attributes[10][is_visible]" class="wcfm-checkbox wcfm_ele simple variable external grouped booking multi_input_block_element" value="enable" data-name="is_visible" checked="checked">'
            html += '<p class="attributes_is_variation_0 wcfm_title checkbox_title wcfm_ele variable variable-subscription wcfm_ele_hide"><strong>Use as Variation</strong></p><label class="screen-reader-text" for="attributes_is_variation_0">Use as Variation</label><input type="checkbox" id="attributes_is_variation_10" name="attributes[10][is_variation]" class="wcfm-checkbox wcfm_ele variable variable-subscription multi_input_block_element wcfm_ele_hide" value="enable" data-name="is_variation" checked="checked">'
            html += '<input type="hidden" id="attributes_tax_name_10" name="attributes[10][tax_name]" class=" multi_input_block_element" value="" data-name="tax_name"><input type="hidden" id="attributes_is_taxonomy_10" name="attributes[10][is_taxonomy]" class=" multi_input_block_element" value="" data-name="is_taxonomy"> <span class="add_multi_input_block multi_input_block_manupulate wcfmfa fa-plus-circle" title="Add New Block" style="display: none;"></span></div> </div>'
       
       attributesDiv.append(html);
    //    $('.content-seperator').css({'display': 'none'})


    //    console.log(attributesDiv);
        return attributesDiv;
    }
    $('.wcfm_add_attribute').click(function (e) { 
        e.preventDefault();
       
        generateHTMLAttributeForm();  
        let spanArrow =  $('.fields_collapser');
     
       
       spanArrow.click(function (e) { 
            e.preventDefault();
            $(this)
            .toggleClass('fa-arrow-circle-up')
            .next('.content-seperator') //select the next accordion panel
            .not(':animated') //if it is not currently animating
            .slideToggle(); 

            // acc = document.getElementsByClassName('content-seperator')

        //    acc = $('.content-seperator');
        //    console.log(acc)
           
        });    
    });
	
} );