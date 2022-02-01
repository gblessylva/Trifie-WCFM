// jQuery(document).ready(function($){

// $(document).ready(function(){

//     $('#frmCSVImport').on('submit', function(event){
  
//       event.preventDefault();
//       $.ajax({
//         url:'/wp-admin/admin-ajax.php?action=upload_csv_endpoint',
//         method:"POST",
//         data:new FormData(this),
//         dataType:'json',
//         contentType:false,
//         cache:false,
//         processData:false,
//         success:function(data)
//         {
//           if(data.error != '')
//           {
//             $('#message').html('<div class="alert alert-danger">'+data.error+'</div>');
//           }
//           else
//           {
//             $('#process_area').html(data.output);
//             $('#upload_area').css('display', 'none');
//           }
//         }
//       });
  
//     });
  
//     var total_selection = 0;
  
//     var first_name = 0;
  
//     var last_name = 0;
  
//     var email = 0;
  
//     var column_data = [];
  
//     // $(document).on('change', '.set_column_data', function(){
  
//     //   var column_name = $(this).val();
  
//     //   var column_number = $(this).data('column_number');
  
//     //   if(column_name in column_data)
//     //   {
//     //     alert('You have already define '+column_name+ ' column');
  
//     //     $(this).val('');
  
//     //     return false;
//     //   }
  
//     //   if(column_name != '')
//     //   {
//     //     column_data[column_name] = column_number;
//     //   }
//     //   else
//     //   {
//     //     const entries = Object.entries(column_data);
  
//     //     for(const [key, value] of entries)
//     //     {
//     //       if(value == column_number)
//     //       {
//     //         delete column_data[key];
//     //       }
//     //     }
//     //   }
  
//     //   total_selection = Object.keys(column_data).length;
  
//     //   if(total_selection == 3)
//     //   {
//     //     $('#import').attr('disabled', false);
  
//     //     first_name = column_data.first_name;
  
//     //     last_name = column_data.last_name;
  
//     //     email = column_data.email;
//     //   }
//     //   else
//     //   {
//     //     $('#import').attr('disabled', 'disabled');
//     //   }
  
//     // });
  
//     // $(document).on('click', '#import', function(event){
  
//     //   event.preventDefault();
  
//     //   $.ajax({
//     //     url:"import.php",
//     //     method:"POST",
//     //     data:{first_name:first_name, last_name:last_name, email:email},
//     //     beforeSend:function(){
//     //       $('#import').attr('disabled', 'disabled');
//     //       $('#import').text('Importing...');
//     //     },
//     //     success:function(data)
//     //     {
//     //       $('#import').attr('disabled', false);
//     //       $('#import').text('Import');
//     //       $('#process_area').css('display', 'none');
//     //       $('#upload_area').css('display', 'block');
//     //       $('#upload_form')[0].reset();
//     //       $('#message').html("<div class='alert alert-success'>"+data+"</div>");
//     //     }
//     //   })
  
//     // });
    
//   });



// });