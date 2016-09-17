<?php

// Register Custom Taxonomy
function alc_taxonomy_standing() {

  $labels = array(
    'name'                       => _x( 'ALC Standings', 'Taxonomy General Name', 'alc_text' ),
    'singular_name'              => _x( 'ALC Standing', 'Taxonomy Singular Name', 'alc_text' ),
    'menu_name'                  => __( 'Standing', 'alc_text' ),
    'all_items'                  => __( 'All Standings', 'alc_text' ),
    'parent_item'                => __( 'Parent Standing', 'alc_text' ),
    'parent_item_colon'          => __( 'Parent Standing:', 'alc_text' ),
    'new_item_name'              => __( 'New Standing', 'alc_text' ),
    'add_new_item'               => __( 'Add New Standing', 'alc_text' ),
    'edit_item'                  => __( 'Edit Standing', 'alc_text' ),
    'update_item'                => __( 'Update Standing', 'alc_text' ),
    'view_item'                  => __( 'View Standing', 'alc_text' ),
    'separate_items_with_commas' => __( 'Separate Standings with commas', 'alc_text' ),
    'add_or_remove_items'        => __( 'Add or remove Standing', 'alc_text' ),
    'choose_from_most_used'      => __( 'Choose from the most used', 'alc_text' ),
    'popular_items'              => __( 'Popular Standings', 'alc_text' ),
    'search_items'               => __( 'Search Standings', 'alc_text' ),
    'not_found'                  => __( 'Not Found', 'alc_text' ),
    'no_terms'                   => __( 'No Standings', 'alc_text' ),
    'items_list'                 => __( 'Standings list', 'alc_text' ),
    'items_list_navigation'      => __( 'Standings list navigation', 'alc_text' ),
  );
  $capabilities = array(
    'manage_terms'               => 'manage_alc_standing',
    'edit_terms'                 => 'manage_alc_standing',
    'delete_terms'               => 'manage_alc_standing',
    'assign_terms'               => 'edit_alc_standing',
  );
  $args = array(
    'labels'                     => $labels,
    'hierarchical'               => true,
    'public'                     => true,
    'show_ui'                    => true,
    'show_admin_column'          => true,
    'show_in_nav_menus'          => false,
    'show_tagcloud'              => false,
    'capabilities'               => $capabilities,
  );
  register_taxonomy( 'alc-standing', array( 'alc' ), $args );

}
add_action( 'init', 'alc_taxonomy_standing', 0 );

// Register Custom Taxonomy
function alc_taxonomy_type() {

  $labels = array(
    'name'                       => _x( 'ALC Types', 'Taxonomy General Name', 'alc_text' ),
    'singular_name'              => _x( 'ALC Type', 'Taxonomy Singular Name', 'alc_text' ),
    'menu_name'                  => __( 'Type', 'alc_text' ),
    'all_items'                  => __( 'All Types', 'alc_text' ),
    'parent_item'                => __( 'Parent Type', 'alc_text' ),
    'parent_item_colon'          => __( 'Parent Type:', 'alc_text' ),
    'new_item_name'              => __( 'New Type', 'alc_text' ),
    'add_new_item'               => __( 'Add New Type', 'alc_text' ),
    'edit_item'                  => __( 'Edit Type', 'alc_text' ),
    'update_item'                => __( 'Update Type', 'alc_text' ),
    'view_item'                  => __( 'View Type', 'alc_text' ),
    'separate_items_with_commas' => __( 'Separate Types with commas', 'alc_text' ),
    'add_or_remove_items'        => __( 'Add or remove Type', 'alc_text' ),
    'choose_from_most_used'      => __( 'Choose from the most used', 'alc_text' ),
    'popular_items'              => __( 'Popular Types', 'alc_text' ),
    'search_items'               => __( 'Search Types', 'alc_text' ),
    'not_found'                  => __( 'Not Found', 'alc_text' ),
    'no_terms'                   => __( 'No Types', 'alc_text' ),
    'items_list'                 => __( 'Types list', 'alc_text' ),
    'items_list_navigation'      => __( 'Types list navigation', 'alc_text' ),
  );
  $capabilities = array(
    'manage_terms'               => 'manage_alc_type',
    'edit_terms'                 => 'manage_alc_type',
    'delete_terms'               => 'manage_alc_type',
    'assign_terms'               => 'edit_alc_type',
  );
  $args = array(
    'labels'                     => $labels,
    'hierarchical'               => true,
    'public'                     => true,
    'show_ui'                    => true,
    'show_admin_column'          => true,
    'show_in_nav_menus'          => false,
    'show_tagcloud'              => false,
    'capabilities'               => $capabilities,
  );
  register_taxonomy( 'alc-type', array( 'alc' ), $args );

}
add_action( 'init', 'alc_taxonomy_type', 0 );

// custom term meta for type taxonomy

// encrypt our form
function edit_form_tag( ) { echo ' enctype="multipart/form-data"'; }
add_action( 'alc-type_term_edit_form_tag' , 'edit_form_tag' );
add_action( 'alc-type_term_new_form_tag' , 'edit_form_tag' );

// allow for SVG upload
function cc_mime_types($mimes) {
  $mimes['svg'] = 'image/svg+xml';
  return $mimes;
}
add_filter('upload_mimes', 'cc_mime_types');

// add form element to taxonomy
add_action( 'alc-type_add_form_fields', 'type_add_group_field', 10, 2 );
function type_add_group_field($taxonomy) {
    ?>
    <div class="form-field term-map-icon">
        <label for="type-map-icon"><?php _e( 'Map Icon', 'alc_text' ); ?></label>
        <!-- Define our actual upload field -->
        <input type="file" name="type-map-icon" value="" />
        <p class="description"><?php _e( 'This currently doesn\'t work, create taxonomy and edit it to add image','alc_text' ); ?></p>
    </div>

    <!-- Create a nonce to validate against -->
    <input type="hidden" name="upload_meta_nonce" value="<?php echo wp_create_nonce( basename( __FILE__ ) ); ?>" />
<?php
}

add_action( 'alc-type_edit_form_fields', 'type_edit_group_field', 10, 2 );
function type_edit_group_field( $term, $taxonomy ){
        var_dump($term);
        // Retrieve our Attachment ID from the post_meta Database Table
        $uploadID   = get_term_meta( $term->term_id, 'type_map_icon', true );
        // Retrieve any upload feedback from the Optoins Database Table
        $feedback   = get_term_meta( $term->term_id, 'type_map_icon_feedback', true );
          ?>

          <tr class="form-field">
            <th scope="row" valign="top"><label for="meta-order"><?php _e( 'Map Icon', 'alc_text' ); ?></label></th>
            <td>
                <div id="mapIcon">

                    <!-- Create a nonce to validate against -->
                    <input type="hidden" name="upload_meta_nonce" value="<?php echo wp_create_nonce( basename( __FILE__ ) ); ?>" />

                    <!-- Define our actual upload field -->
                    <label for="type-map-icon"><?php _e('Upload an SVG', 'alc_text') ?></label>
                    <input type="file" name="type-map-icon" value="" />

                    <?php 
                          if( is_numeric( $uploadID ) ) : // IF our upload ID is actually numeric, proceed

                            /***
                            /*  In this case we are pulling an image, if we are uploading
                            /*  something such as a PDF we could use the built-in function
                            /*  wp_get_attachment_url( $id );
                            /*  codex.wordpress.org/Function_Reference/wp_get_attachment_url
                            ***/
                            $imageArr = wp_get_attachment_url( $uploadID );     // Get the URL of file
                            $imageURL = $imageArr;                             // wp_get_attachment_image_src() returns an array, index 0 is our URL
                            ?>

                            <div id="uploaded_image">
                                <a href="post.php?post=<?php echo $uploadID; ?>&action=edit" target="_blank">Edit Image</a><br />

                                <!-- Display our image using the URL retrieved earlier -->
                                <a href="post.php?post=<?php echo $uploadID; ?>&action=edit" target="_blank"><img src="<?php echo $imageURL; ?>" /></a><br /><br />
                            </div>

                            <!-- IF we received feedback, something went wrong and we need to show that feedback. -->               
                    <?php elseif( ! empty( $feedback ) ) : ?>

                        <p style="color:red;font-size:12px;font-weight;bold;font-style:italic;"><?php echo $feedback; ?></p>

                    <?php endif; ?>

                </div>
                <span class="description"><?php _e( 'Upload an appropriate image.' ); ?></span>
                <br />
                <br />

                <!-- This link is for our deletion process -->
                <?php if( ! empty( $uploadID ) ) : ?>

                <a href="javascript:void(0)" class="deleteImage" style="color:red;text-decoration:underline;">Delete</a>

            <?php endif; ?>

        </td> 
    </tr>

          <?php
        /** Since we've shown the user the feedback they need to see, we can delete our meta **/
        // delete_term_meta( $term->term_id, 'type_map_icon_feedback' );
}

add_action( 'created_alc-type', 'type_save_meta', 10, 2 );
add_action( 'edited_alc-type', 'type_save_meta', 10, 2 );
function type_save_meta( $term_id, $tt_id ){
    $uploadFeedback = __('Upload attempted','alc_text');
    // Make sure that the nonce is set, taxonomy is set, and that our uploaded file is not empty
    if(
      isset( $_POST['upload_meta_nonce'] ) && 
      wp_verify_nonce( $_POST['upload_meta_nonce'], basename( __FILE__ ) ) &&
      isset( $_POST['taxonomy'] ) && 
      isset( $_FILES['type-map-icon'] ) && 
      !empty( $_FILES['type-map-icon'] )
      ) {
        // Only accept image mime types. - List of mimetypes: http://en.wikipedia.org/wiki/Internet_media_type
        $supportedTypes = array( 'image/svg+xml', 'image/png' );
        // Get the mime type and extension.
        $fileArray      = wp_check_filetype( basename( $_FILES['type-map-icon']['name'] ) );
        // Store our file type
        $fileType       = $fileArray['type'];

        // Verify that the type given is what we're expecting
        if( in_array( $fileType, $supportedTypes ) ) {
            // Let WordPress handle the upload
            $uploadStatus = wp_handle_upload( $_FILES['type-map-icon'], array( 'test_form' => false ) );

            // Make sure that the file was uploaded correctly, without error
            if( isset( $uploadStatus['file'] ) ) {
                require_once(ABSPATH . "wp-admin" . '/includes/image.php');

                // Let's add the image to our media library so we get access to metadata
                $imageID = wp_insert_attachment( array(
                    'post_mime_type'    => $uploadStatus['type'],
                    'post_title'        => preg_replace( '/\.[^.]+$/', '', basename( $uploadStatus['file'] ) ),
                    'post_content'      => '',
                    'post_status'       => 'publish'
                    ),
                $uploadStatus['file']
                );

                // Generate our attachment metadata then update the file.
                $attachmentData = wp_generate_attachment_metadata( $imageID, $uploadStatus['file'] );
                wp_update_attachment_metadata( $imageID,  $attachmentData );


                // IF a file already exists in this meta, grab it
                $existingImage = get_term_meta( $term_id, 'type_map_icon', true );
                // IF the meta does exist, delete it.
                if( ! empty( $existingImage ) && is_numeric( $existingImage ) ) {
                    wp_delete_attachment( $existingImage );
                    // Update our meta with the new attachment ID
                    update_term_meta( $term_id, 'type_map_icon', $imageID );
                    // Just in case there's a feedback meta, delete it
                    delete_term_meta( $term_id, 'type_map_icon_feedback' );
                } else {
                    // If a file doesn't exist add the meta
                    add_term_meta( $term_id, 'type_map_icon', $imageID );
                }

            }
            else {
                // Something major went wrong, enable debugging
                $uploadFeedback = 'There was a problem with your uploaded file. Contact Administrator.';
            }
        }
        else {
            // Wrong file type
            $uploadFeedback = __('File must be SVG or PNG','alc_text');
        }

        // Update our Feedback meta
        if( isset( $uploadFeedback ) ) {
            update_term_meta( $term_id, 'type_map_icon_feedback', $uploadFeedback );
        }
    }
}

// // add column to taxonomy list
// add_filter('manage_edit-alc-type_columns', 'type_add_group_column' );
// function type_add_group_column( $columns ){}

// add_filter('manage_alc-type_custom_column', 'type_add_group_column_content', 10, 3 );
// function type_add_group_column_content( $content, $column_name, $term_id ){}