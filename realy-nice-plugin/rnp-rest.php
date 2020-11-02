<?php

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
        $count = 0;

        forEach($title as $t) {
          if (substr_count(strtolower(get_the_title()), strtolower($t)) > 0) 
            $count += substr_count(strtolower(get_the_title()), strtolower($t));
        }

        if ($count > 0) {
          $people = get_post_meta(get_the_ID(), '_event_people')[0];
          $temp = array();

          forEach($people as $person) {
            array_push($temp, array(
              'personName' => $person[0],
              'personLastname' => $person[1],
              'personUrl' => $person[2]
            ));
          } 
          $people = $temp;
          $posts = get_post_meta(get_the_ID(), '_event_posts')[0];

          array_push($res, array(
            'postTitle' => get_the_title(),
            'postUrl' => get_permalink(),
            'importantPeople' => $people,
            'eventPosts' => $posts,
            'searchRaiting' => $count
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

        $people = get_post_meta(get_the_ID(), '_event_people')[0];
        $temp = array();

        forEach($people as $person) {
          array_push($temp, array(
            'personName' => $person[0],
            'personLastname' => $person[1],
            'personUrl' => $person[2]
          ));
        } 
        $people = $temp;
        $posts = get_post_meta(get_the_ID(), '_event_posts')[0];

        array_push($res, array(
          'postTitle' => get_the_title(),
          'postUrl' => get_permalink(),
          'importantPeople' => $people,
          'eventPosts' => $posts,
          'searchRaiting' => $count
        ));
      }
    }
    wp_reset_postdata();

    return $res;
  }
  else 
    return false;

}