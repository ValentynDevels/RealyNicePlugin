<?php

get_header(); ?>

  <main>
    <div class="container single">

      <a href="<?php echo get_post_type_archive_link('old_events'); ?>">archive</a>

      <img width="770px" height="510px" src="<?php the_post_thumbnail_url() ?>">

      <div class="like-block">
      <svg class="like-svg" width="25" height="23" viewBox="0 0 25 23" fill="none" xmlns="http://www.w3.org/2000/svg">
          <path data-id="<?php the_ID(); ?>" id="like" d="M22.5732 2.05667C19.8974 -0.223599 15.918 0.186558 13.4619 2.72074L12.5 3.71195L11.5381 2.72074C9.0869 0.186558 5.10253 -0.223599 2.42675 2.05667C-0.63966 4.67386 -0.800793 9.37113 1.94335 12.208L11.3916 21.9639C12.0019 22.5938 12.9932 22.5938 13.6035 21.9639L23.0517 12.208C25.8008 9.37113 25.6396 4.67386 22.5732 2.05667V2.05667Z"/>
      </svg>
      <span class="like-count">0</span>
      </div>

      <h3><?php the_title(); ?></h3>

      <p><?php echo get_post_meta($post->ID, '_event-fragment_meta_key')[0]; ?></p>

      <p><?php the_content(); ?></p>

      <h4><?php echo get_post_meta($post->ID, '_event-date_meta_key')[0]; ?></h4>
    </div>
  </main>

  <?php comments_template(); ?>


  <?php get_footer();