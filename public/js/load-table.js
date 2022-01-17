jQuery(document).ready(function($){
    // Load tables
    // var dt = $('#prodigi_products').DataTable({  
    //     "processing": true,
    //     "serverSide": true,  
    //     "responsive": false,
    //     "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
    //     'pageLength': 10,
    //     "pagingType": "full_numbers",

    //     ajax: {
    //         url: "/wp-admin/admin-ajax.php?action=datatables_endpoint",
    //         // url: "./test.json",
    //         cache:false,
    //     },
    //     columns: [
    //         // { data: 'image' },
    //         { data: 'title' },
    //         { data: 'author' },
    //         { data: 'sku' },
    //         { data: 'price' },
    //         { data: 'categories' },
    //         // { data: 'actions' },
    //         { data: 'prodigi_sku' },
    //         { data: 'action' }
    //     ],
    //     // columnDefs: [
    //     //     {
    //     //         "render": function (data, type, row) {
    //     //         return '<a href="' + {data: 'link'} + '">' + data + '</a>';
    //     //         },
    //     //         "targets": 0,
    //     //     }
    //     // ],


    // }); //.DataTable()


 
/** and column with  */
var columns = {
    author: 'Store',
    title: 'Title',
    price: 'Price',
    sku: 'SKU',
    prodigi_sku: 'Prodigi SKU' ,

    categories: 'Categories',
    attributes: 'Attributes',
    action: 'Action',
};

var table = $('#table-sortable').tableSortable({

    data: [],
    columns: columns,
    rowsPerPage: 10,
    pagination: true,
    totalPages: 3,
    sorting: true,
    sortingIcons: {
        asc: '<span>▼</span>',
        desc: '<span>▲</span>',
    },
    searchField: '#searchField',

    formatCell: function(row, key) {
        if (key === 'Title') {
            return $('<span></span>').addClass('font-weight-bold').text(row[key]);
        }
        if (key === 'author') {
            return $('<span></span>').addClass('text-uppercase').text(row[key]);
        }
        // Finally return cell for rest of columns;
        return row[key];
    },

});

$.get('/wp-admin/admin-ajax.php?action=datatables_endpoint', function(result) {
    // Push data into existing data
   
     results = result.data;
    
    // console.log(results);
     table.setData(results, null, true);

    // or Set new data on table, columns is optional.
    table.setData(results, columns);

}).done(function() {
   let testTable = $('.gs-table-body tr:nth-child(even)')
//  
   testTable.css('background-color', 'red') ;
   console.log(testTable.css);
  
})

$('#changeRows').on('change', function() {
    table.updateRowsPerPage(parseInt($(this).val(), 10));
  })
// table.lookUp('search string', /* optional */ ['column1', 'column2', 'column3']);
// console.log(table.options);


    
});


