<?php

/**
 * Advanced Product Type
 */
class WC_Product_Wdm extends WC_Product{
    public function __construct( $product ) {
       $this->product_type = 'wdm_custom_product';
       parent::__construct( $product );
       // add additional functions here
    }
}

new WC_Product_Wd();