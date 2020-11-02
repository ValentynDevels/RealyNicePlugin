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

// Get and send info about posts with date for calendar
add_action('wp_ajax_posts', 'send_posts');
add_action('wp_ajax_nopriv_posts', 'send_posts');

function send_posts() {
  if (isset($_GET['title'])) {
    $title = explode('+', $_GET['title']);

    $args = array(
      'post_type' => 'post',
    );
    
    $query = new WP_Query($args);
    
    if ($query->have_posts()) {
    
      $posts = array();

      while ($query->have_posts()) {
        $query->the_post();
        $count = 0;

        forEach($title as $t) {
          if (substr_count(strtolower(get_the_title()), strtolower($t)) > 0) 
            $count += substr_count(strtolower(get_the_title()), strtolower($t));
        }

        if ($count > 0) {
          array_push($posts, array(
            'title' => get_the_title(), 
            'raiting' => $count,
            'postId' => get_the_ID()
          ));
        }
      }
      $json = json_encode($posts);
      echo $json;
    }
    exit;
  }
  exit;
}
