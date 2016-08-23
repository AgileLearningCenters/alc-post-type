<?php 

$geocodeAPIKey = 'AIzaSyDUkTyad56hKDcQaCOJOWDsundLnWFI3Fc';

function alc_post_type_admin_script() {
    global $post_type, $geocodeAPIKey;
    if( 'alc' == $post_type )
    wp_enqueue_script( 'google-maps-api', 'https://maps.googleapis.com/maps/api/js?key=' . $geocodeAPIKey, array(), null, true );
    wp_enqueue_script( 'alc-post-type-js', plugins_url( 'scripts/alc-post-type.js' , __FILE__ ), array('google-maps-api'), '1.0.1', true );
}

add_action( 'admin_print_scripts-post-new.php', 'alc_post_type_admin_script', 11 );
add_action( 'admin_print_scripts-post.php', 'alc_post_type_admin_script', 11 );