<?php

// Old events post type
function rnp_old_events_post_type() {
    register_post_type('old_events', array(
      'label' => 'Old Events',
      'labels' => array(
          'name' => 'Old Events', 
          'singular_name' => 'Old Event',
          'add_new' => 'Add new Event',
          'add_new_item'       => 'Add new Event',
                'edit_item'          => 'Edit Event',
                'new_item'           => 'New Event',
                'view_item'          => 'View Event',
                'search_items'       => 'Search Event',
                'not_found'          =>  'Events not found',
                'not_found_in_trash' => 'Not found',
                'menu_name'          => 'Old Events'
        ),
      'description' => 'Post type Old Event is for add events information abaut events which have already taken place before. You can add title, content, image, date for event and other.',
      'public' => true,
      'show_ui' => true,
      'show_in_menu' => true,
      'show_in_rest' => false,
      'menu_position' => 5,
      'menu_icon' => 'dashicons-calendar-alt',
      'hierarchical' => false,
      'supports' => array('title', 'editor', 'thumbnail','comments'),
      'taxonomies' => array('importance'),
      'has_archive' => true,
    ));
  }