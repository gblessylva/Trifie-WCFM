<?php
global $wpdb;


// Import CSV
if(isset($_POST['butimport'])){

  // File extension
  $extension = pathinfo($_FILES['import_file']['name'], PATHINFO_EXTENSION);

  // If file extension is 'csv'
  if(!empty($_FILES['import_file']['name']) && $extension == 'csv'){

    $totalInserted = 0;

    // Open file in read mode
    $csvFile = fopen($_FILES['import_file']['tmp_name'], 'r');

    fgetcsv($csvFile); // Skipping header row
    while(($csvData = fgetcsv($csvFile)) !== FALSE){
          $csvData = array_map("utf8_encode", $csvData);
          
        // Row column length
        $dataLen = count($csvData);
        $title = trim($csvData[0]);
        $trifie_sku_recomended_size = trim($csvData[1]);
        $Category = trim($csvData[2]);
        $prodigi_trifie_sku = trim($csvData[3]);
        $trifie_product_id = trim($csvData[4]);
        $trifie_product_cost = trim($csvData[5]);
        $trifie_product_min_price = trim($csvData[6]);
        $slug = strtolower(str_replace(' ', '-', $prodigi_trifie_sku));


    $added_cat = wp_insert_term(
        $Category,
        'trifie_sku_category',
        array(
            'slug'        => $Category
        )
    );
    if(!is_wp_error($added_cat)){
        // var_dump($added_cat);
      $cat_id = $added_cat['term_id'];
      $cat_slug = $added_cat['slug'];
      $new_category = get_category_by_slug( $cat_slug );
      echo 'term_id: '.$cat_id.'<br>';
    }

    // var_dump($added_cat);
    // $cat_id = $added_cat['term_id'];
    // echo 'term_id: '.$cat_id.'<br>';
    $post_id = -1;

	// Setup the author, slug, and title for the post
	$author_id = 1;

	// If the page doesn't already exist, then create it
        if( null == get_page_by_title( $title ) ) {

            // Set the post ID so that we know the post was created successfully
            $post_id = wp_insert_post(
                array(
                    'comment_status'	=>	'closed',
                    'ping_status'		=>	'closed',
                    'post_author'		=>	$author_id,
                    'post_name'		=>	$slug,
                    'post_title'		=>	$title,
                    'post_status'		=>	'publish',
                    'post_type'		=>	'trifie_sku',
                )


            );
        
        add_post_meta($post_id, 'trifie_sku_recomended_size', $trifie_sku_recomended_size, true);
        wp_set_post_terms($post_id, array($cat_id), 'trifie_sku_category', true);
        add_post_meta($post_id, 'prodigi_trifie_sku', $prodigi_trifie_sku, true);   
        add_post_meta($post_id, 'trifie_product_id', $trifie_product_id, true); 
        add_post_meta($post_id, 'trifie_product_cost', $trifie_product_cost, true);
        add_post_meta($post_id, 'trifie_product_min_price', $trifie_product_min_price, true);
        


        echo '<div class="updated"><p>'.$title.' has been successfully imported.</p></div>';
        // Otherwise, we'll stop
        } else {

                // Arbitrarily use -2 to indicate that the page with the title already exists
                $post_id = -2;
                echo 'Post already exists';

        } // end if

    }
  }


}


// Custom upload



    

?>
<h2>All Entries</h2>

<!-- Form -->
<form method='post' action='<?= $_SERVER['REQUEST_URI']; ?>' enctype='multipart/form-data'>
  <input type="file" name="import_file" >
  <input type="submit" name="butimport" value="Import">
</form>


