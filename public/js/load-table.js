$product_type = '';	
$product_cat = '';
$product_taxonomy = {};
$product_vendor = '';
products = [];
jQuery(document).ready(function($) {
    // Copy product Templates
  $('#copy_template').click(function(){
    console.log("cliked")
  });

    $.ajax({
      url: "/wp-admin/admin-ajax.php?action=datatables_endpoint",
      type: "POST",
      dataType: "json",
      ajaxStart: function() {
        $('#loader').show();
     },
     complete: function(){
        $('#loader').hide();
     },
      success: function(data) {        
          new gridjs.Grid({
            // columns: ['Product Name', 'SKU', 'Min Price', 'Action'],
            columns: [
              {
                  name: 'Product Name',
                  formatter: (function (cell) {
                      return gridjs.html('' + cell + '');
                  })
              },
              "SKU", 
              {
                  name: 'Status',
                  formatter: (function (cell) {
                      return gridjs.html('' + cell + '');
                  })
              },
              "Price", "Min Price", "Printable SKU",
              {
                  name: 'Actions',
                  width: '120px',
                  formatter: (function (cell) {
                      return gridjs.html(cell);
                  })
              },
          ],
            data: data,
            style: {
                table: {
                
                },
                th: {
                  'background-color': 'rgba(0, 0, 0)',
                  color: '#fff',
                  'text-align': 'left',
                  'font-size': '20px',
                },
                td: {
                  'text-align': 'left',
                  'table-layout': 'fixed',
                  'width': '200px',
                  'border-bottom': '1px solid #d1d1d1',
                  'font-size' : '16px',
                  padding : '15px',
                },
              },
            search: true,
            sort: true,
            pagination: {
              limit: 10,
              summary: false
            }
          }).render(document.getElementById("grid-wrapper"));
    
      },

      error: function(jqXHR, textStatus, errorThrown) {
        // Handle any errors here
        console.error("Ajax error: " + textStatus + " - " + errorThrown);
      }
    });
  });





jQuery(document).ready(function($){

    $product_vendor = GetURLParameter( 'product_vendor' );
    // Load tables
    var dt = $('#product-templates').DataTable({  
        "processing": true,
		    "responsive": true,
		    "paging": true,
        ajax: {
          type   : "POST",
					caching: false,
					nonce : 'nonce',
          url    : '/wp-admin/admin-ajax.php?action=datatables_endpoint',
          "data"   : function( d ) {
            d.action     = 'load_template_products',
            d.controller = 'wcfm-cpt1'
            // d.cpt1_cat      = $cpt1_cat,
            // d.cpt1_vendor   = $cpt1_vendor,
            // d.cpt1_status   = GetURLParameter( 'cpt1_status' )
          },
          "complete" : function () {
            initiateTip();
            // if (typeof intiateWCFMuQuickEdit !== 'undefined' && $.isFunction(intiateWCFMuQuickEdit)) intiateWCFMuQuickEdit();
                                        // Fire wcfm-products table refresh complete
               $( document.body ).trigger( 'updated_wcfm-products' );
                }
                 }
					  } );
    
    }); 

