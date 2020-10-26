<?php
// change likes amount
add_action('wp_ajax_likos', 'change_amount_likes');
add_action('wp_ajax_nopriv_likos', 'change_amount_likes');

function change_amount_likes() {
  if (isset($_POST['postID']) && isset($_POST['like'])) {
    $postID = $_POST['postID'];

    $meta = (int) get_post_meta($postID, 'like_count', true);

    if (!$meta) {
      update_post_meta($postID, 'like_count', 0);
      $meta = (int) get_post_meta($postID, 'like_count', true);
    }
    if ($_POST['like'] == 'yes')
      $meta += 1;
    else if ($_POST['like'] == 'no') 
      $meta -= 1;
    else if ($_POST['like'] == 'first') {
      wp_die($meta);
      return;
    }
    
    update_post_meta($postID, 'like_count', $meta);
    wp_die($meta);
  }
  else {
    wp_die('error');
  }
}

// Get new posts for load more ajax
  add_action('wp_ajax_loadmore', 'get_more_posts');
  add_action('wp_ajax_nopriv_loadmore', 'get_more_posts');

  function get_more_posts() {
    if (isset($_POST['ids'])) {
      $arr = explode(',', $_POST['ids']);
      
      $args = array(
        'post_type' => 'old_events',
        'posts_per_page' => get_option('default_options')['amount'],
        'post__not_in' => $arr,
      );

      $posts = array();

      $query = new Wp_query($args);

      while ($query->have_posts()): $query->the_post();
        array_push($posts, array( 
          "id" => get_the_ID(),
          "link" => get_permalink(),
          "img" => get_the_post_thumbnail_url( get_the_ID() ),
          "title" => get_the_title(),
          "fragment" => get_post_meta(get_the_ID(), '_event-fragment_meta_key')[0]
        ));
      
      endwhile;
      wp_reset_postdata();
      
      $json = json_encode($posts);
      echo $json;
    }
    exit;
  }

// Get and send info about posts with date for calendar
add_action('wp_ajax_calendar', 'send_data_calendar');
add_action('wp_ajax_nopriv_calendar', 'send_data_calendar');

function send_data_calendar() {
  if (isset($_POST['calendar'])) {
    $args = array(
      'post_type' => 'old_events',
      'meta_key' => '_event-date_meta_key',
    );
    
    $query = new WP_Query($args);
    
    if ($query->have_posts()) {
    
      $posts = array();

      while ($query->have_posts()) {
        $query->the_post();

        $date = get_post_meta(get_the_ID(), '_event-date_meta_key')[0];
        $date = explode('-', $date);
        $day_zero = $date[2];
        if ($date[2][0] == "0") {
          $date[2] = $date[2][1];
        }

        array_push($posts, array( 
          "year" => $date[0],
          "month" => $date[1],
          "dayZero" => $day_zero,
          "day" => $date[2], 
        ));
      }
      $json = json_encode($posts);
      echo $json;
    }
    exit;
  }
  exit;
}

// ajax for important people in admin bar
add_action('wp_ajax_newperson', 'some_ajax');
add_action('wp_ajax_nopriv_newperson', 'some_ajax');

function some_ajax($post) {

  if (isset($_POST['newperson'])) {

    wp_die(
      '<div class="people_small_wrapper">
      <div class="people_meta_input">
      <label>First name</label><input name="person_name" type="text"  placeholder="Ivan"/>
    </div>
    <div class="people_meta_input">
      <label>Last name</label><input name="person_last_name" type="text"  placeholder="Superman"/>
    </div>
    <div class="people_meta_input">
      <label>Person url name</label><input name="person_url" type="url"  placeholder="https://ivan.com"/>
    </div></div>'
    );
  }
  if ( isset($_POST['name']) ) {

    $name = $_POST['name'];
    $lastName = $_POST['lastName'];
    $url = $_POST['url'];
    $post_id = $_POST['postId'];

    $meta_arr = get_post_meta($post_id, '_event_people');

    $lastPerson = array_pop($meta_arr[0]);

    if ($lastPerson[0] == $name && $lastPerson[1] == $lastName && $lastPerson[2] == $url) {
      update_post_meta($post_id, '_event_people', $meta_arr[0]);
      wp_die('You delete last person');
    }

    exit();
  }
}