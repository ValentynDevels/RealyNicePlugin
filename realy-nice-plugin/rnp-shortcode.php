<?php

// shortcode for paste some amount of posts
add_shortcode( 'paste_posts' , 'paste_posts_func' );

function paste_posts_func($atts) {

  switch ($atts['importance']) {
    case '1':
      $raiting = ['2', '3', '4', '5'];
      break;
    case '2':
      $raiting = ['3', '4', '5'];
      break;
    case '3':
      $raiting = ['4', '5'];
      break;
    case '4':
      $raiting = ['5'];
      break;
    case '5':
      $raiting = ['5'];
      break;
    
    default:
      $raiting = ['1', '2', '3', '4', '5'];
      break;
  }


  if ($atts['from'] && $atts['to']) {
    $args = array(
      'post_type' => 'old_events',
      'importance' => $raiting,
      'meta_query' => array(
        array(
          'key' => '_event-date_meta_key',
          'value' => [$atts['from'], $atts['to']],
          'compare' => 'BETWEEN',
          'type' => 'DATE',
        ),
      ),
    );
  }
  else {
    $args = array(
      'post_type' => 'old_events',
      'importance' => $raiting,
      'posts_per_page' => $atts['quantity'],
    );
  }
  
  $query = new WP_Query($args);

  while ($query->have_posts()): $query->the_post(); ?>
    <div class="post">
    <a href="<?php the_permalink() ?>" class="post_url">
      <img src="<?php the_post_thumbnail_url();?>" width="500px">

      <h4 class="post__title"><?php  the_title(); ?></h4 class="post__title">

    </a>

    <p class="fragment" style="margin: 0;"> <?php echo get_post_meta(get_the_ID(), '_event-fragment_meta_key')[0];?></p>
    </div>
    
  <?php endwhile; 
    wp_reset_postdata();
}