<?php

function fabs_player_get_mp3s($options=array())
{
    global $wpdb;
    
    $defaults = array(
        'only_links' => false,
        'category' => false,
        'post_id' => false,
        'post_slug' => false
    );
    
    $options = array_merge($defaults, $options);
    
    $where = array();
    if( $options['only_links'] ){
        $where[] = "p2.post_status = 'publish'";
        $where[] = "p2.post_content LIKE CONCAT('%',p1.guid,'%')";
    }
    if( $options['post_id'] ){
        $where[] = 'p1.ID = '.$options['post_id'];
    }
    if( $options['post_slug'] ){
        $where[] = "p1.slug = '".$options['post_slug']."'";
    }
    
    $mime_where = wp_post_mime_type_where('audio', 'p1' );
    $q = "SELECT p1.post_title AS title, p1.guid AS mp3, thumb.guid AS thumbnail, p2.ID AS parent_id, p2.guid AS link, p2.post_title AS parent_title "
        ."FROM $wpdb->posts p1 "
        ."LEFT JOIN $wpdb->posts p2 ON p2.ID = p1.post_parent "
        ."LEFT JOIN $wpdb->postmeta meta ON p2.ID = meta.post_id AND meta.meta_key = '_thumbnail_id' "
        ."LEFT JOIN $wpdb->posts thumb ON thumb.ID = meta.meta_value "
        ."WHERE 1=1 ".$mime_where." "
        ." ".implode(' AND ', $where)." "
        ."ORDER BY p2.post_date DESC, p1.menu_order ASC";
    $mp3s = $wpdb->get_results($q);
    
    foreach( $mp3s as &$mp3 ){
        $mp3->thumb = get_the_post_thumbnail($mp3->parent_id);
    }
    
    return $mp3s;
}