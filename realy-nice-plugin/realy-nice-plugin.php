<?php
/*
  Plugin Name: Realy Nice Plugin
  Author: Indus
  Version: 1.0.0
  Description: It is a realy nice plugin!!! Realy nice!!!!
*/
//include functions and widget


  require __DIR__ . '/functions.php';
  require __DIR__ . '/calendar-widget.php';
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
	
