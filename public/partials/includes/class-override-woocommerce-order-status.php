<?php

add_filter( 'woocommerce_get_order_item_totals', 'customize_email_order_line_totals', 1000, 3 );
function customize_email_order_line_totals( $total_rows, $order, $tax_display ){
    // Only on emails notifications
    if( ! is_wc_endpoint_url() || ! is_admin() ) {
        // Remove shipping line from totals rows
        unset($total_rows['shipping']);
    }
    return $total_rows;
}