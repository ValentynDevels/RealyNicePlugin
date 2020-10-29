<?php

//include styles and scripts
function rnp_scripts() {
  // CSS styles
  wp_enqueue_style('mainCSS', plugins_url('/css/realy-nice-plugin.css', __FILE__), array(), '1.0.0');

  // add custom css for old archive and old posts
  if (is_post_type_archive('old_events') || is_singular('old_events')) {
    $custom_css = get_option('default_options');
    wp_add_inline_style('mainCSS', $custom_css['customcss']);
  }

  wp_enqueue_script('mainJS', plugins_url('/js/realy-nice-plugin.js', __FILE__), array(), '1.0.0', true);

  // include likes to post pages
  if (is_singular('old_events')) {
    wp_enqueue_script('likeJS', plugins_url('/js/rnp-like.js', __FILE__), array(), '1.0.0', true);
  }
  
  // add object with variables to js files
  wp_localize_script('mainJS', 'likesOBJ', array(
    'url' => admin_url('admin-ajax.php'),
    'archive_url' => get_post_type_archive_link('old_events'),
    'domain' => get_site_url(),
  ));
  wp_localize_script('likeJS', 'likesOBJ', array(
    'url' => admin_url('admin-ajax.php'),
    'archive_url' => get_post_type_archive_link('old_events'),
  ));
}

// custom css for wordpress admin
function rnp_admin_style() {
  wp_enqueue_style('admin_styles', plugins_url('/css/rnp_admin_theme.css', __FILE__), array(), '1.0.0');
  wp_enqueue_style('select2CSS', 'https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/css/select2.min.css', array(), null);

  wp_enqueue_script('admin_scripts', plugins_url('/js/rnp-admin.js', __FILE__), array(), '1.0.0', true);
  wp_enqueue_script('select2', 'https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/js/select2.min.js', array(), 'nu', true);
  wp_enqueue_script('jquery');
  
  wp_localize_script('admin_scripts', 'likesOBJ', array(
    'url' => admin_url('admin-ajax.php'),
  ));
}
add_action('admin_enqueue_scripts', 'rnp_admin_style');
add_action('login_enqueue_scripts', 'rnp_admin_style');

// swap default templates on plugin templates
add_filter('template_include', 'load_rnp_templates');

function load_rnp_templates($template) {

  if (is_singular('old_events')) 
    load_single_template();
  else if (is_post_type_archive( 'old_events' )) 
    load_archive_template();
  else
    return $template;
}

// old_events posts template
function load_single_template() {
  require __DIR__ . '/templates/single-old_events.php';
}

// old_events archive template
function load_archive_template() {
  require __DIR__ . '/templates/archive-old_events.php';
}

add_action( 'widgets_init', 'reg_space_for_old_search' );
function reg_space_for_old_search(){
	register_sidebar( array(
		'name'          => 'Old events search',
		'id'            => 'old_events_search',
		'description'   => 'This is space for search widget (old events)',
		'class'         => '',
		'before_widget' => '<div id="archive_widget_id" class="archive_widget">',
		'after_widget'  => "</div>\n",
		'before_title'  => '<h6 class="widgettitle">',
		'after_title'   => "</h6>\n",
	) );
}

//Create two metaboxes for Old Events post type 
function realy_nice_fields() {
  add_meta_box('rnp_meta_box', 'Event fragment and fragment date', 'fragment_callback', 'old_events', 'normal', 'low', array(
    '__back_compat_meta_box' => false,
  ));
  add_meta_box('rnp_people_box', 'Important people on the event', 'people_metabox_callback', 'old_events', 'normal', 'high', array(
    '__back_compat_meta_box' => false,
  ));
  add_meta_box('additional_posts', 'Posts for the old event', 'posts_callback', 'old_events', 'normal', 'high', array(
    '__back_compat_meta_box' => false,
  ));
}

function posts_callback($post, $meta) {
  wp_nonce_field( plugin_basename(__FILE__), 'posts_nonce' ); 
  $query = new WP_Query('post_type=post');

  if (get_post_meta($post->ID, '_event_post_ids')[0])
    $posts_arr = get_post_meta($post->ID, '_event_post_ids')[0];
  else 
    $posts_arr = false;
//selected="selected"
  ?>

    <select class="js-example-basic-multiple" name="states[]" multiple="multiple">
      <?php 
        if ($query->have_posts()) {
          while ($query->have_posts()): $query->the_post(); 

          if ($posts_arr) {
            foreach($posts_arr as $one_post) {
              if ($one_post == get_the_ID()) {
                $selected_attr = 'selected="selected"';
                break;
              }
              else 
                $selected_attr = '';
            }
          }

          ?>

          <option <?php echo $selected_attr; ?> data-event-id="<?php echo $post->ID ?>" data-id="<?php the_ID(); ?>"><?php the_title(); ?></option>
          

          <?php

          endwhile; wp_reset_postdata(); 
        }
      ?>
    </select>

<?php

}

function people_metabox_callback($post, $meta) {

  wp_nonce_field( plugin_basename(__FILE__), 'people_nonce' ); 

  $meta_people_data = get_post_meta( $post->ID, '_event_people', 1 ); 

  ?>
  <div class="people_meta_wrapper">
    <?php foreach($meta_people_data as $meta) { ?>
     <div class="people_small_wrapper">
      <div class="people_meta_input">
          <label>First name</label><input value="<?php echo $meta[0]; ?>" name="person_name" type="text"  placeholder="Vitalik" required/>
        </div>
        <div class="people_meta_input">
          <label>Last name</label><input value="<?php echo $meta[1]; ?>" name="person_last_name" type="text"  placeholder="Superman" required/>
        </div>
        <div class="people_meta_input">
          <label>Person url name</label><input value="<?php echo $meta[2]; ?>" name="person_url" type="url"  placeholder="http://vitalic.com" required/>
      </div> 
     </div>
    <?php } ?>
  </div>
  <div class="buttons-wrapper">
    <button data-id="<?php echo $post->ID; ?>" id="add_new_person">Add new</button>
    <button data-id="<?php echo $post->ID; ?>" id="delete_rec_person">Delete recent</button>
  </div>

  <?php
}

add_action( 'save_post', 'rnp_save_people_meta' );

function rnp_save_people_meta( $post_id ) {

	if ( ! wp_verify_nonce( $_POST['myplugin_noncename'], plugin_basename(__FILE__) ) )
		return;

	if ( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE ) 
		return;

	if( ! current_user_can( 'edit_post', $post_id ) )
    return;

  $people = get_post_meta($post_id, '_event_people', 1);

  if (!$people) 
    $people = array();
  //get data from fields
  $i = 1;

  while (isset($_POST['person_name' . $i])) {
    $person_name = sanitize_text_field( $_POST['person_name' . $i] );
    $person_last_name = sanitize_text_field( $_POST['person_last_name' . $i] );
    $person_url = sanitize_text_field( $_POST['person_url' . $i] );

    array_push($people, array($person_name, $person_last_name, $person_url));

    $i++;
  }

	//update info in bd
  update_post_meta( $post_id, '_event_people', $people );
}

//callback for fragment metabox
function fragment_callback($post, $meta) {
  $screens = $meta['args'];

	wp_nonce_field( plugin_basename(__FILE__), 'myplugin_noncename' );

  $fragment_value = get_post_meta( $post->ID, '_event-fragment_meta_key', 1 );
	$date_value = get_post_meta( $post->ID, '_event-date_meta_key', 1 );
  
  echo '<label id="frgment_label" for="fragment_field">Old event fragment</label>
  <textarea id="fragment_field" name="rnp_fragment_meta" placeholder="'. $fragment_value .'">
  '. $fragment_value .'</textarea>';

  echo '<label id="date_label" for="date_field">Old event date</label><input type="date" id="date_field" name="rnp_date_meta" value="'. $date_value .'" />';

}


add_action( 'save_post', 'rnp_save_postdata' );

function rnp_save_postdata( $post_id ) {

  if ( ! isset( $_POST['rnp_fragment_meta'] )
    || ! isset( $_POST['rnp_date_meta'] ) )
		return; 
	if ( ! wp_verify_nonce( $_POST['myplugin_noncename'], plugin_basename(__FILE__) ) )
		return;

	if ( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE ) 
		return;

	if( ! current_user_can( 'edit_post', $post_id ) )
		return;

  //get data from fields
  $fragment_data = sanitize_text_field( $_POST['rnp_fragment_meta'] );
  $date_data = sanitize_text_field( $_POST['rnp_date_meta'] );

	//update info in bd
  update_post_meta( $post_id, '_event-fragment_meta_key', $fragment_data );
  update_post_meta( $post_id, '_event-date_meta_key', $date_data );
}

//register taxonomy importance for old events
add_action( 'init', 'rnp_tax_reg' );

function rnp_tax_reg() {
  register_taxonomy('importance', 'old_events', 
  [
		'labels'                => [
			'name'              => 'Importance',
			'singular_name'     => 'Importance',
			'search_items'      => 'Search Importance',
			'all_items'         => 'All Importance',
			'view_item '        => 'View Importance',
			'parent_item'       => 'Parent Importance',
			'parent_item_colon' => 'Parent Importance:',
			'edit_item'         => 'Edit Importance',
			'update_item'       => 'Update Importance',
			'add_new_item'      => 'Add New Importance',
			'new_item_name'     => 'New Importance Name',
			'menu_name'         => 'Importance',
		],
		'description'           => 'Add Importance for Old Events', 
		'public'                => true,
		'hierarchical'          => true,

		'rewrite'               => true,
		'show_admin_column'     => false, 
		'show_in_rest'          => null, 
		'rest_base'             => null, 
  ] );
}

//add five terms for importance taxonomy
function add_my_terms() {
  for ($i = 1; $i < 6; $i++) {
    wp_insert_term( '' . $i, 'importance', array(
      'description' => '',
      'slug'        => $i,
    ) );
  }
}

add_action('init', 'add_my_terms');

//create new menu for rnp settings
add_action( 'admin_menu', 'rnp_reg_settings_page' );

function rnp_reg_settings_page() {
  add_menu_page( 'Realy nice plugin Settings', 'Realy nice plugin', 'edit_published_posts', 'rnp-settings', 'npm_interface_output', 'dashicons-admin-generic', 66 );
}

function npm_interface_output() {
  ?>
	<div class="wrap">
		<h2><?php echo get_admin_page_title() ?></h2>

		<form action="options.php" method="POST">
			<?php
				settings_fields( 'default group' ); 
				do_settings_sections( 'default_section' ); 
				submit_button();
			?>
		</form>
	</div>
	<?php
}

add_action( 'admin_menu', 'plugin_settings' );

function plugin_settings() {
	register_setting( 'default group', 'default_options', 'sanitize_callback' );

	add_settings_section( 'default_settings', 'Main settings', '', 'default_section' ); 

  add_settings_field('amount_posts', 'Amount of posts in Archive page', 'fill_amount', 'default_section', 'default_settings' );
  
  add_settings_field('pag_or_more', 'You want use pagination or "Load more?', 'fill_per_or_more', 'default_section', 'default_settings' );

  add_settings_field('custom_css', 'Add your custom css for Old Events archive page and Old Events post page', 'fill_custom_css', 'default_section', 'default_settings' );
}

//fill option 1 (amount)
function fill_amount(){
	$val = get_option('default_options');
	$val = $val ? $val['amount'] : null;
	?>
	<input type="number" min="1" name="default_options[amount]" value="<?php echo esc_attr( $val ) ?>" />
	<?php
}

//fill option 2 (pag_or_more)
function fill_per_or_more(){
	$val = get_option('default_options');
	$val = $val ? $val['radio'] : null;
	?>
	<label>
    <input type="radio" value="pag" name="default_options[radio]" 
      <?php
        if ($val == 'pag')
      echo "checked"; ?>
    /> Pagination
  </label>
  <label>
    <input type="radio" value="ldme" name="default_options[radio]" 
      <?php
          if ($val == 'ldme')
        echo "checked"; ?>
    /> "Load more"
  </label>
	<?php
}

//fill option 3 (custom css)
function fill_custom_css(){
	$val = get_option('default_options');
	$val = $val ? $val['customcss'] : null;
	?>
	<label><textarea name="default_options[customcss]" placeholder="custom css"><?php echo $val ?></textarea></label>
	<?php
}

//clearing data
function sanitize_callback( $options ){ 

	foreach( $options as $name => & $val ){
		if( $name == 'amount' )
      if ($val < 1)
        $val = 1;
      
    if (name == 'customcss')
      $val = strip_tags( $val );
	}

	return $options;
}


// reg route for search 
add_action( 'rest_api_init', 'reg_route_frontsearch');

function reg_route_frontsearch() {

  register_rest_route( 'rnp/v1', '/old_events/(?P<title>\w+)', 
    array(
      array(
        'methods'             => 'GET',        
        'callback'            => 'rnp_rest_callback',  
      ),
      array(
        'methods'  => 'POST',
        'callback' => 'if_filters',
        'args'     => array(
          'from' => array(
            'type'     => 'string',
            'required' => true,    
          ),
          'to' => array(
            'type'     => 'string',
            'required' => true,    
          ),
          'imp' => array(
            'type'     => 'integer',
            'required' => true,    
          ),
        ),
      )
    )
  );
}

function rnp_rest_callback( WP_REST_Request $request ) {
  $title = explode('_', $request->get_param('title'));

  $query = new WP_Query(array(
    'post_type' => 'old_events',
  ));

  if ($query->have_posts()) {
    $res = array();
    $count = 0;

      while ($query->have_posts()) {
        $query->the_post();

        forEach($title as $t) {
          if (substr_count(strtolower(get_the_title()), strtolower($t)) > 0) 
            $count += substr_count(strtolower(get_the_title()), strtolower($t));
        }

        if ($count > 0) {
          $postsids = get_post_meta(get_the_ID(), '_event_post_ids');
          
          array_push($res, array(
            get_the_title(),
            get_permalink(),
            get_post_meta(get_the_ID(), '_event_people'),
            $postsids,
            $count
          ));
        }
      }
      wp_reset_postdata();
     
    return $res;
  }
  else 
    return new WP_Error('no_events_with_the_id', 'Not found anyone posts', array( 'status' => 404 ));
}

function if_filters(WP_REST_Request $request) {
  $from = $request->get_param('from');
  $to = $request->get_param('to');
  $imp = (string) $request->get_param('imp');
  $title = explode('_', $request->get_param('title'));

  if ($imp != '0') {
    $imp = str_split($imp);

    $args = array(
      'post_type' => 'old_events',
      'importance' => $imp,
      'meta_query' => array(
        array(
          'key' => '_event-date_meta_key',
          'value' => [$from, $to],
          'compare' => 'BETWEEN',
          'type' => 'DATE',
        ),
      ),
    );
  }
  else if ($imp == '0') {
    $args = array(
      'post_type' => 'old_events',
      'meta_query' => array(
        array(
          'key' => '_event-date_meta_key',
          'value' => [$from, $to],
          'compare' => 'BETWEEN',
          'type' => 'DATE',
        ),
      ),
    );
  }

  $query = new WP_Query($args);
  $res = array();
  
  if ($query->have_posts()) {
    while ($query->have_posts()) {
      $query->the_post();

      $count = 0;

      forEach($title as $t) {
        if (substr_count(strtolower(get_the_title()), strtolower($t)) > 0) 
            $count += substr_count(strtolower(get_the_title()), strtolower($t));
      }
      if ($count > 0) {

        $postsids = get_post_meta(get_the_ID(), '_event_post_ids');

        array_push($res, array(
          get_the_title(), 
          get_permalink(),
          get_post_meta(get_the_ID(), '_event_people'),
          $postsids,
          $count
        ));
      }
    }
    wp_reset_postdata();

    return $res;
  }
  else 
    return false;

}








