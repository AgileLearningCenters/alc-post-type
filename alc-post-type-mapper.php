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
    add_shortcode('alc_member_map', array($this, 'map_shortcode'));
  }

  /*
  * The default attributes for the map shortcode
  * @since 1.0.0
  * @var array $override attribute defaults for use in map shortcode
  * 
  * @return array of attributes
  */
  public function map_shortcode_attributes() {
    return array(
        'target' => false,
        'legend' => true,
        'class' => 'map',
        'zoom' => 3,
        'mapWidth' => '100%',
        'mapHeight' => '400px',
        'centerLat' => '30.136093332022153',
        'centerLng' => '-100.47842740624996',
    );
  }

  /*
  * Builds the map shortcode
  * @since 1.0.0
  */
  public function map_shortcode( $atts ) {
    $a = shortcode_atts( array(
        'target' => false,
        'legend' => true,
        'class' => 'map',
        'zoom' => 3,
        'mapWidth' => '100%',
        'mapHeight' => '400px',
        'centerLat' => '30.136093332022153',
        'centerLng' => '-100.47842740624996',
    ), $atts );

    $members_data = $this->get_member_data();

    echo '<pre>';
    print_r( $this->get_member_data() );
    echo '</pre>';
  }

  /*
  * Query post type for members
  * @since 1.0.0
  * 
  * @return posts object
  */
  public function get_members( $args = null ) {
    $default = array(
        'post_type' => $this->member_post_type,
        'post_status' => 'publish',
        'meta_query' => array(
          // only query members should be on the map
          array(
            'key'   => 'alc_map_info_on_map',
            'value' => '1',
            )
          )
    );

    $r = wp_parse_args( $args, $defaults );

    return get_posts( $r );
  }

  /*
  * Prepares member data from shortcode 
  * @since 1.0.0
  * 
  * @return array of members
  */
  public function get_member_data( $members = null ) {
    if ( !isset( $members )) {
      $members = $this->get_members();
    }
    if( $members ) {
      $r = array(); # it would be cool if we just passed the whole object with meta data attached
      foreach ( $members as $member ) {
        $meta  = $this->get_member_meta( $member, $this->member_post_meta_prefix );
        $terms = $this->get_member_terms( $member, 'alc-type' );

        $r[$member->post_name] = array_merge(
          $meta,
          array( 'alc_type' => array_merge( $terms[0], array( 'map_icon' => $this->get_member_map_icon( $terms ) ) ) ),
          array( 'infoWindow' => $this->construct_map_info_window()),
          (array) $member
          );
      }
      return $r;
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
  * Query 
  * @since 1.0.0
  * 
  * @return posts object
  */
  public function get_member_terms($member, $term ) {

    $r = array();

    $terms = wp_get_post_terms($member->ID, $term );
  }

  /*
  * Query 
  * @since 1.0.0
  * @var array $terms an object of post terms
  * 
  * @return array $args {
  *     @type int $id the media id of icon.
  *     @type string $url the media url of icon.
  * }
  */
  public function get_member_map_icon($terms) {
    $icon_meta_key = 'type-icon-id';
    $r = array();

    $r['id'] = get_term_meta( $terms[0]->term_id, $icon_meta_key, true );
    $r['url'] = wp_get_attachment_url( $r['id'] );

    return $r;
  }

  /*
  * Query 
  * @since 1.0.0
  * @var array $d multidimensional array of member meta data
  * 
  * @return string $srt HTML for inside of map info windows
  */
  public function construct_map_info_window($d) {
    $map = $d['alc']['map'];
    ob_start();
    ?>
<h1><?php echo $map['info_name'] ?></h1>
<p><?php echo $map['info_description'] ?></p>
<?php if ( isset($map['info_cta']) && !empty($map['info_cta']) ) : ?>
<p style="text-align:center;">
  <a class="button" href="<?php echo $d['alc']['map']['info_cta'] ?>">
    <?php echo $map['info_cta_label'] ?>
  </a>
</p>
<p>Visit <a href="<?php echo $d['alc']['profile']['website'] ?>">Our website</a></p>
<?php endif; ?>

    <?php
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
