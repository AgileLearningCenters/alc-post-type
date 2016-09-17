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

/**
 * Thanks https://catapultthemes.com/adding-an-image-upload-field-to-categories/
 **/
if ( ! class_exists( 'ALC_TYPE_MAP_ICON' ) ) {

class ALC_TYPE_MAP_ICON {

    public function __construct() {
    //
    }

    /*
    * Initialize the class and start calling our hooks and filters
    * @since 1.0.0
    */
    public function init() {
        add_action( 'alc-type_add_form_fields', array ( $this, 'add_type_icon' ), 10, 2 );
        add_action( 'created_alc-type', array ( $this, 'save_type_icon' ), 10, 2 );
        add_action( 'alc-type_edit_form_fields', array ( $this, 'update_type_icon' ), 10, 2 );
        add_action( 'edited_alc-type', array ( $this, 'updated_type_icon' ), 10, 2 );
        add_action( 'admin_enqueue_scripts', array ( $this, 'load_wp_media_files' ) );
        add_action( 'admin_footer', array ( $this, 'add_script' ) );
        
        add_filter( 'upload_mimes', array ( $this, 'add_mime_types' ) );
        add_filter( 'manage_edit-alc-type_columns', array ( $this, 'add_group_column' ) );
        add_filter( 'manage_alc-type_custom_column', array ( $this, 'add_group_column_content' ), 10, 3 );
    }

    /*
    * Enqueue media script for file upload
    * @since 1.0.0
    */
    public function load_wp_media_files() {
        wp_enqueue_media();
    }

    /*
    * Allow for SVG upload
    * @since 1.0.0
    */
    function add_mime_types($mimes) {
      $mimes['svg'] = 'image/svg+xml';
      return $mimes;
    }

    /*
    * Add a form field in the new taxonomy page
    * @since 1.0.0
    */
    public function add_type_icon ( $taxonomy ) { ?>
        <div class="form-field term-group">
         <label for="type-icon-id"><?php _e('Map Icon', 'alc_text'); ?></label>
         <input type="hidden" id="type-icon-id" name="type-icon-id" class="custom_media_url" value="">
         <div id="type-icon-wrapper"></div>
         <p>
           <input type="button" class="button button-secondary ct_tax_media_button" id="ct_tax_media_button" name="ct_tax_media_button" value="<?php _e( 'Add Map Icon', 'alc_text' ); ?>" />
           <input type="button" class="button button-secondary ct_tax_media_remove" id="ct_tax_media_remove" name="ct_tax_media_remove" value="<?php _e( 'Remove Map Icon', 'alc_text' ); ?>" />
        </p>
         <p><?php _e('For best results use SVG or PNG file', 'alc_text'); ?></p>
        </div>
    <?php
    }

    /*
    * Save the form field
    * @since 1.0.0
    */
    public function save_type_icon ( $term_id, $tt_id ) {
        if( isset( $_POST['type-icon-id'] ) && '' !== $_POST['type-icon-id'] ){
         $image = $_POST['type-icon-id'];
         add_term_meta( $term_id, 'type-icon-id', $image, true );
        }
    }

    /*
    * Edit the form field
    * @since 1.0.0
    */
    public function update_type_icon ( $term, $taxonomy ) { ?>
        <tr class="form-field term-group-wrap">
         <th scope="row">
           <label for="type-icon-id"><?php _e( 'Map Icon', 'alc_text' ); ?></label>
         </th>
         <td>
           <?php $image_id = get_term_meta ( $term -> term_id, 'type-icon-id', true ); ?>
           <input type="hidden" id="type-icon-id" name="type-icon-id" value="<?php echo $image_id; ?>">
           <div id="type-icon-wrapper">
             <?php if ( $image_id ) { ?>
             <img class="custom_media_image" src="<?php echo wp_get_attachment_url ( $image_id ); ?>" style="margin:0;padding:0;max-height:100px;max-width:100px;" />
             <?php } ?>
           </div>
           <p>
             <input type="button" class="button button-secondary ct_tax_media_button" id="ct_tax_media_button" name="ct_tax_media_button" value="<?php _e( 'Add Map Icon', 'alc_text' ); ?>" />
             <input type="button" class="button button-secondary ct_tax_media_remove" id="ct_tax_media_remove" name="ct_tax_media_remove" value="<?php _e( 'Remove Map Icon', 'alc_text' ); ?>" />
           </p>
           <p class="description"><?php _e('For best results use SVG or PNG file', 'alc_text'); ?></p>
         </td>
        </tr>
    <?php
    }

    /*
    * Update the form field value
    * @since 1.0.0
    */
    public function updated_type_icon ( $term_id, $tt_id ) {
        if( isset( $_POST['type-icon-id'] ) && '' !== $_POST['type-icon-id'] ){
            $image = $_POST['type-icon-id'];
            update_term_meta ( $term_id, 'type-icon-id', $image );
        } else {
            update_term_meta ( $term_id, 'type-icon-id', '' );
        }
    }

    /*
    * Add script
    * @since 1.0.0
    */
    public function add_script() { ?>
    <script>
     jQuery(document).ready( function($) {
       function type_icon_upload(button_class) {
         var _custom_media = true,
             _orig_send_attachment = wp.media.editor.send.attachment;
         $('body').on('click', button_class, function(e) {
           var button_id = '#'+$(this).attr('id');
           var send_attachment_bkp = wp.media.editor.send.attachment;
           var button = $(button_id);
           _custom_media = true;
           wp.media.editor.send.attachment = function(props, attachment){
            console.log(attachment);
             if ( _custom_media ) {
               $('#type-icon-id').val(attachment.id);
                console.log(attachment.id);
               $('#type-icon-wrapper').html('<img class="custom_media_image" src="" style="margin:0;padding:0;max-height:100px;max-width:100px;" />');
               $('#type-icon-wrapper .custom_media_image').attr('src', attachment.url).css('display','block');
             } else {
               return _orig_send_attachment.apply( button_id, [props, attachment] );
             }
            }
         wp.media.editor.open();
         return false;
       });
     }
     type_icon_upload('.ct_tax_media_button.button'); 
     $('body').on('click','.ct_tax_media_remove',function(){
       $('#type-icon-id').val('');
       $('#type-icon-wrapper').html('<img class="custom_media_image" src="" style="margin:0;padding:0;max-height:100px;float:none;" />');
     });
     // Thanks: http://stackoverflow.com/questions/15281995/wordpress-create-category-ajax-response
     $(document).ajaxComplete(function(event, xhr, settings) {
       var queryStringArr = settings.data.split('&');
       if( $.inArray('action=add-tag', queryStringArr) !== -1 ){
         var xml = xhr.responseXML;
         $response = $(xml).find('term_id').text();
         if($response!=""){
           // Clear the thumb image
           $('#type-icon-wrapper').html('');
         }
       }
     });
    });
    </script>
    <?php }

    /*
    * Add column to taxonomy display
    * @since 1.0.0
    */
    public function add_group_column( $columns ){
        $columns['type-icon'] = __( 'Map Icon', 'alc_text' );
        return $columns;
    }

    /*
    * Enqueue media script for file upload
    * @since 1.0.0
    */
    public function add_group_column_content( $content, $column_name, $term_id ){

        if( $column_name !== 'type-icon' ){
            return $content;
        }

        $term_id = absint( $term_id );
        $attachment_id = get_term_meta( $term_id, 'type-icon-id', true );

        if( !empty( $attachment_id ) ){
            $content .= '<img class="type-map-icon" src="' . wp_get_attachment_url ( $attachment_id ) . '" style="margin:0;padding:0;max-height:100px;max-width:100px;" />';
        }

        return $content;
    }

}

$ALC_TYPE_MAP_ICON = new ALC_TYPE_MAP_ICON();
$ALC_TYPE_MAP_ICON -> init();

}