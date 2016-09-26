<?php
/**
 * Summary (no period for file headers)
 *
 * Description. (use period)
 *
 * @link URL
 *
 * @package WordPress
 * @subpackage Component
 * @since x.x.x (when the file was introduced)
 */

/**
 * Summary.
 *
 * Description.
 *
 * @since x.x.x
 */

new alc_post_mapping();

class alc_post_mapping {

  protected $APIKey   = 'AIzaSyDUkTyad56hKDcQaCOJOWDsundLnWFI3Fc';
  protected $MapAPIKey = 'AIzaSyCXre9Yr0X1YQpFZJpXWIWN8ZOVTHZUjvU';
  public $member_post_type = 'alc';
  public $member_post_meta_prefix = 'alc_';

  public function __construct() {
    add_action( 'wp_enqueue_scripts', array($this, 'map_shortcode_scripts' ));
    add_action( 'admin_print_scripts-post-new.php', array($this, 'map_admin_scripts' ), 11 );
    add_action( 'admin_print_scripts-post.php', array($this, 'map_admin_scripts' ), 11 );

    add_shortcode('alc_member_map', array($this, 'map_shortcode'));
  }

  /*
  * Register needed scripts
  * @since 1.0.0
  */
  public function map_shortcode_scripts() {
    wp_register_script( 'google-maps-api', 'https://maps.googleapis.com/maps/api/js?key=' . $this->APIKey, array(), null, true );
    wp_register_script( 'google-maps-cluster-api', plugins_url( 'scripts/google-map-markerclusterer.js' , __FILE__ ), array('google-maps-api'), '1.0.1', true );
    wp_register_script( 'alc-map-js', plugins_url( 'scripts/alc-mapper.js' , __FILE__ ), array('google-maps-api','google-maps-cluster-api'), '1.0.1', true );
  }

  /*
  * Register admin scripts
  * @since 1.0.0
  */
  public function map_admin_scripts() {
    global $post_type;
    if( 'alc' == $post_type )
    wp_enqueue_script( 'google-maps-api', 'https://maps.googleapis.com/maps/api/js?key=' . $this->APIKey, array(), null, true );
    wp_enqueue_script( 'alc-post-type-js', plugins_url( 'scripts/alc-post-type.js' , __FILE__ ), array('google-maps-api'), '1.0.1', true );
  }

  /*
  * Builds the map shortcode
  * @since 1.0.0
  * @var array $atts attribute defined by user in shortcode
  *
  * @return string container div if target is not set by user
  */
  public function map_shortcode( $atts ) {
    $default = uniqid('member-map-');
    $a = shortcode_atts( array(
        'target' => $default,
        'legend' => $default . '-legend',
        'class' => 'map',
        'zoom' => 3,
        'mapWidth' => '100%',
        'mapHeight' => '400px',
        'centerLat' => '30.136093332022153',
        'centerLng' => '-100.47842740624996',
        'defaultIcon' => plugins_url( 'img/map-icon-default.png' , __FILE__ ),
        'clusterIcon' => plugins_url( 'img/map-icon-cluster' , __FILE__ )
    ), $atts );

    $members_data = $this->get_member_data( array( 'settings' => $a ) );

    // Add data json to membersMapData object
    $json = 'membersMapData["' . $a['target'] . '"] = ' . json_encode($members_data);
    wp_add_inline_script('alc-map-js',$json);

    wp_enqueue_script('google-maps-api');
    wp_enqueue_script('alc-map-js');

    if ( !isset($atts['target']) ) {
      return '<div id="' . $a['target'] . '"></div>';
    }

  }

  /*
  * Query post type for members
  * @since 1.0.0
  * @var array $args optional. arguments for post query
  * 
  * @return posts object
  */
  public function get_members( $args = false ) {
    $default = array(
        'post_type' => $this->member_post_type,
        'post_status' => 'publish',
        'numberposts' => -1,
        'meta_query' => array(
          // only query members should be on the map
          array(
            'key'   => 'alc_map_info_on_map',
            'value' => '1',
            )
          )
    );

    if ( $args ) {
      $r = wp_parse_args( $args, $defaults );
    } else {
      $r = $default;
    }

    return get_posts( $r );
  }

  /*
  * Prepares member data for shortcode 
  * @since 1.0.0
  * @var array $extra optional. Array of data that will be passed on to the data object. Default: empty array
  * @var object $members optional. Post query return object. Default: false
  * 
  * @return array $args An array of data about each member
  */
  public function get_member_data( $extra = array(), $members = false ) {
    if ( ! $members ) {
      $members = $this->get_members();
    }
    if( $members ) {
      $r = array(); # it would be cool if we just passed the whole object with meta data attached
      foreach ( $members as $member ) {
        $terms               = $this->get_member_terms( $member, 'alc-type' );
        $meta                = $this->get_member_meta( $member, $this->member_post_meta_prefix );
        $meta['type']        = array_merge( (array) $terms[0], array( 'icon' => $this->get_member_map_icon( $terms[0] ) ) );
        $meta['post']        = (array) $member;
        $meta['infoWindow']  = $this->construct_map_info_window($meta);

        $r[$member->post_name] = $meta;
      }
      return array_merge( array( 'alcs' => $r ), $extra );
    }
  }

  /*
  * Takes a post object and matches post meta data sub string
  * @since 1.0.0
  * 
  * @return array 
  *   alc_profile_facebook_url is split into alc => profile => facebook_url
  */
  public function get_member_meta($member, $match ) {
    
    $r = array();

    foreach (get_post_meta($member->ID) as $key => $value) {
      if (substr($key, 0, strlen($match)) === $match) {
        $value = (is_array($value)) ? implode(' ', $value) : $value ;
        //explode $key by underscore
        $key = explode('_', $key, 3);
        $r[$key[0]][$key[1]][$key[2]] = $value;
      }
    }

    return $r;
  }

  /*
  * Returns the post terms for a specific ID and term 
  * @since 1.0.0
  * 
  * @return posts object
  */
  public function get_member_terms($member, $term ) {
    return wp_get_post_terms($member->ID, $term );
  }

  /*
  * Get the map icon from the term 
  * @since 1.0.0
  * @var array $term a object containing a single term
  * 
  * @return array $args {
  *     @type int $id the media id of icon.
  *     @type string $url the media url of icon.
  * }
  */
  public function get_member_map_icon($term) {
    $icon_meta_key = 'type-icon-id';
    $r = array();

    $r['id']  = get_term_meta( $term->term_id, $icon_meta_key, true );
    $r['url'] = wp_get_attachment_url( $r['id'] );

    return $r;
  }

  /*
  * Constructs the map info HTML 
  * @since 1.0.0
  * @var array $d multidimensional array of member meta data
  * 
  * @return string $srt HTML for inside of map info windows
  */
  public function construct_map_info_window($d) {
    $map = $d['alc']['map'];
    ob_start();
?><h2><?php echo $map['info_name'] ?></h2>
<p><?php echo $map['info_description'] ?></p>
<?php if ( isset($map['info_cta']) && !empty($map['info_cta']) ) : ?>
<p style="text-align:center;">
  <a class="button" href="<?php echo $d['alc']['map']['info_cta'] ?>">
    <?php echo $map['info_cta_label'] ?>
  </a>
</p>
<p>Visit <a href="<?php echo $d['alc']['profile']['website'] ?>">Our website</a></p>
<?php endif; ?><?php
    $str = ob_get_contents();
    ob_end_clean();

    return $str;

  }

  /*
  * Tests keys and builds link if they exist 
  * @since 1.0.0
  * @var array $d an array of metadata
  * 
  * @return 
  */
  public function construct_info_window_link($d,$keys) {
  }
}
