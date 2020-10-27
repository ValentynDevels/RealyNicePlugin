<?php
/*
  Plugin Name: Realy Nice Plugin
  Author: Indus
  Version: 1.0.0
  Description: It is a realy nice plugin!!! Realy nice!!!!
*/

register_activation_hook( __FILE__, 'rnp_new_role' );

function rnp_new_role() {

  //include functions and widget
  require __DIR__ . '/functions.php';
  require __DIR__ . '/rnp-widgets.php';
  require __DIR__ . '/rnp-ajax.php';
  require __DIR__ . '/rnp-shortcode.php';
  require __DIR__ . '/cli-events-delete.php';
  require __DIR__ . '/rnp-old_events-post_type.php';

  // wp_enqueue_scripts
  add_action('wp_enqueue_scripts', 'rnp_scripts');

  //create custom post type Old Events
  add_action('init', 'rnp_old_events_post_type');

  // add realy nice fields to Old Events post type
  add_action('add_meta_boxes', 'realy_nice_fields', 1);

  // create new role for posts and pages redactor
  add_role( 'rn_redactor', 'Vitalik', 
    array(
      'read' => true,
      'edit_posts' => true,
      'edit_published_posts' => true,
      'edit_others_posts' => true,
      'publish_posts' => true,
      'edit_pages' => true,
      'edit_published_pages' => true,
      'edit_others_pages' => true,
      'publish_pages' => true,
      ''
    )
  );
}
