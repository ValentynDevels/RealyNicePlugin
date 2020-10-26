<?php 
if( ! defined('WP_UNINSTALL_PLUGIN') ) exit;


// delete comments for old_events posts 
$comments = get_comments(['post_type' => 'old_events']);
foreach( $comments as $comment ){
    wp_delete_comment($comment, true);
}

// delete old_events metadata and posts
$query = new WP_query(['post_type' => 'old_events']);

if ($query->have_posts()) {
    while ($query->have_posts()) {
        $query->the_post();

        delete_post_meta( get_the_ID(), '_event-fragment_meta_key');
        delete_post_meta( get_the_ID(), '_event-date_meta_key');
        delete_post_meta( get_the_ID(), '_event_people');
        delete_post_meta( get_the_ID(), 'like_count');

        wp_delete_post( get_the_ID(), true );
    }
}

delete_option('default_options');

die();
