<?php
// require_once('');
if ( is_admin() ) {
    add_action( 'admin_menu', 'add_products_menu_entry', 100 );
}

function add_products_menu_entry() {
    add_submenu_page(
        'edit.php?post_type=product',
        __( 'Product Template Upload' ),
        __( 'Upload Product Template' ),
        'manage_woocommerce', // Required user capability
        'product-template-upload',
        'generate_template_upload_page'
    );
}

function generate_template_upload_page() {
     $form = '';
    $form .= '<form class="form-horizontal" action="'.$_SERVER["REQUEST_URI"].'" method="post"
        name="frmCSVImport" id="frmCSVImport"
        enctype="multipart/form-data">
        <div class="input-row">
            <label class="col-md-4 control-label">Choose CSVs
                File</label> <input type="file" name="template-file"
                id="file" accept=".csv">
            <button type="submit" id="submit" name="import-template"
                class="btn-submit">Import</button>
            <br />

        </div>';

    echo $form;
    
;

}
