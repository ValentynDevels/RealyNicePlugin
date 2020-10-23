<?php

// WP_CLI Delete events
$delete_events = function( $args, $assoc_args ) {
    if ($assoc_args['importance'] != 1) {
      switch ($assoc_args['importance']) {
        case 2:
          $importance = ['1'];
          break;
        case 3:
          $importance = ['1', '2'];
          break;
        case 4:
          $importance = ['1', '2', '3'];
          break;
        case 5:
          $importance = ['1', '2', '3', '4'];
          break;
      }
  
      $query = new WP_Query(array(
        'post_type' => 'old_events',
        'importance' => $importance
      ));
  
      if ($query->have_posts()) {
        while ($query->have_posts()) {
         $query->the_post(); 
          $deleted = wp_delete_post(get_the_ID());
          if ($deleted)
            WP_CLI::success( "Delated post $deleted->ID, $deleted->post_title" );
          else 
            WP_CLI::error("i not have posts");
        }
        wp_reset_postdata();
      }
      else 
        WP_CLI::error("Not found posts with importance $importance");
    }
    if (isset($assoc_args['date'])) {
      $delete_date = $assoc_args['date'];
  
      $query = new WP_Query(array(
        'post_type' => 'old_events',
        'meta_query' => array(
          array(
            'key' => '_event-date_meta_key',
            'value' => $delete_date,
            'compare' => '<',
            'type' => 'DATE',
          ),
        ),
      ));
      
      if ($query->have_posts()) {
        while ($query->have_posts()) {
         $query->the_post(); 
          $deleted = wp_delete_post(get_the_ID());
          if ($deleted)
            WP_CLI::success( "Delated post $deleted->ID, $deleted->post_title" );
          else 
            WP_CLI::error("i not have posts");
        }
        wp_reset_postdata();
      }
    }
    if (($assoc_args['importance'] == 1) && !isset($assoc_args['date']))
      WP_CLI::error("give me arguments please!");  
  };
  if ( defined( 'WP_CLI' ) && WP_CLI )
  WP_CLI::add_command( 'old_events delete', $delete_events, array(
    'shortdesc' => 'Delete old events',
    'synopsis' => array(
        array(
            'type'        => 'assoc',
            'name'        => 'date',
            'description' => 'Уvents with a date older than this will be deleted.',
            'optional'    => true,
        ),
        array(
          'type'        => 'assoc',
          'name'        => 'importance',
          'description' => 'Less important events will be deleted.',
          'optional'    => true,
          'default' => '1',
          'options' => array('1', '2', '3', '4', '5'),
      ),
    ),
    'when' => 'after_wp_load',
    'longdesc' =>   '## EXAMPLES' . "\n\n" . 'wp old_events delete --date=2020-10-24',
  ) );