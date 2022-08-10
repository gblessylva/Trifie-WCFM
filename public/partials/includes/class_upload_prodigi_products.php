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
        $post_slug = strtolower(str_replace(' ', '-', $title));


    $added_cat = wp_insert_term(
        $Category,
        'trifie_sku_category',
        array(
            'slug'        => $Category
        )
    );
    $cat_id;
    
    // Check if category already exists
    $term = get_term_by('name', $Category, 'trifie_sku_category');
    if($term){
      $cat_id = $term->term_id;
    }else{
      $cat_id = $added_cat['term_id'];
    }

    
   
    $post_id = -1;

	// Setup the author, slug, and title for the post
	$author_id = 1;

  // Get post by slug
  $post_check = get_page_by_title($title, OBJECT, 'trifie_sku');

//  check if post_chek is in trash

// if($post_check->post_status != 'publish'){
//   echo 'post is trash';
// }

  // If the post does not already exist, create it
  if($post_check->post_status != 'publish' ){
  
    $post_id = wp_insert_post(array(
        'post_title' => $title,
        'post_content' => $trifie_product_id,
        'post_status' => 'publish',
        'post_author' => $author_id,
        'post_type' => 'trifie_sku',
        'post_name' => $post_slug,
        'comment_status'	=>	'closed',
        'ping_status'		=>	'closed',
    ));
    wp_set_post_categories( $post_id, $cat_id );

    add_post_meta($post_id, 'trifie_sku_recomended_size', $trifie_sku_recomended_size, true);
    wp_set_post_terms($post_id, array($cat_id), 'trifie_sku_category', true);
    add_post_meta($post_id, 'prodigi_trifie_sku', $prodigi_trifie_sku, true);   
    add_post_meta($post_id, 'trifie_product_id', $trifie_product_id, true); 
    add_post_meta($post_id, 'trifie_product_cost', $trifie_product_cost, true);
    add_post_meta($post_id, 'trifie_product_min_price', $trifie_product_min_price, true);
    


    echo '<div class="updated"><p>'.$title.' has been successfully imported.</p></div>';
    // }
  }else{
    $post_id = $post_check->ID;
    echo '<div class="alert alert-danger">';
    echo '<strong>'.$prodigi_trifie_sku.'</strong> already exists.';
    echo '</div>';

  }

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


