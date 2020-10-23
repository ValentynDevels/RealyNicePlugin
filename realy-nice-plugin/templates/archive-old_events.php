<?php 

get_header(); ?>

  <main>
    <div class="container"> <?php

    $paged = $_GET['page'];

      if (isset($_GET['day'])) {
        $calendar_date = $_GET['year'] . '-' . $_GET['month'] . '-' . $_GET['day'];

        $args = array(
          'post_type' => 'old_events',
          'posts_per_page' => get_option('default_options')['amount'],
          'paged' => $paged,
          'meta_query' => array(
            array(
              'key'     => '_event-date_meta_key',
              'value'   => $calendar_date,
            )
          )
        );
      }
      else {
        $args = array(
          'post_type' => 'old_events',
          'posts_per_page' => get_option('default_options')['amount'],
          'paged' => $paged,
        );       
      }
      
      $query = new WP_Query($args); ?>

      <div class="posts"> <?php

      while ($query->have_posts()): $query->the_post(); ?>
      
        <div class="post" data-id="<?php the_ID(); ?>">
        <a href="<?php the_permalink() ?>" class="post_url">
          <img src="<?php the_post_thumbnail_url();?>" width="500px">

          <h4 class="post__title"><?php  the_title(); ?></h4>

        </a>

        <p class="fragment" style="margin: 0;"> <?php echo get_post_meta(get_the_ID(), '_event-fragment_meta_key')[0];?></p>
        </div>
        
      <?php endwhile;
      
      ?> </div> <?php

        if (get_option('default_options')['radio'] == 'pag') {

          $args = array(
            'base'         => '%_%',
            'format'       => '?page=%#%',
            'total'        => $query->max_num_pages,
            'current'      => max( 1, $paged),
            'prev_next'    => false,
            'type'         => 'list',
          ); 
          
          echo paginate_links( $args ); 

          wp_reset_postdata();
        }
        else if (get_option('default_options')['radio'] == 'ldme'
        && $query->max_num_pages > 1) {
          ?> 
          <a href="#" class="load_more">Load more!</a>

        <?php }

        ?>  </div>

        

  </main>

  <?php get_footer();