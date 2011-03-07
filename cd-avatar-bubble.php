<?php
/*
Plugin Name: CD BuddyPress Avatar Bubble
Plugin URI: http://cosydale.com/plugin-cd-avatar-bubble.html
Description: After moving your mouse pointer on a BuddyPress user avatar you will see a bubble with the defined by admin information about this user.
Version: 1.2.3
Author: slaFFik
Author URI: http://cosydale.com/
Site Wide Only: true
*/
define ('CD_AB_VERSION', '1.2.3');
define ('CD_AB_IMAGE_URI', WP_PLUGIN_URL . '/cd-bp-avatar-bubble/_inc/images');

register_activation_hook( __FILE__, 'cd_ab_activation');
register_deactivation_hook( __FILE__, 'cd_ab_deactivation');
function cd_ab_activation() {
    $cd_ab['color'] = 'blue';
    $cd_ab['borders'] = 'images';
    
    $cd_ab['access'] = 'all';
    
    $cd_ab['messages'] = 'yes';
    $cd_ab['friend'] = 'no';
    
    $cd_ab['action'] = 'click';
    $cd_ab['delay'] = '0';
    
    $cd_ab['groups'] = 'off';
    
    add_option('cd_ab', $cd_ab, '', 'yes');
}
function cd_ab_deactivation() { delete_option('cd_ab'); }

/* LOAD LANGUAGES */
function cd_ab_load_textdomain() {
    $locale = apply_filters('buddypress_locale', get_locale() );
    $mofile = dirname( __File__ )   . "/langs/$locale.mo";

    if ( file_exists( $mofile ) )
        load_textdomain('cd_ab', $mofile );
}
add_action ('plugins_loaded', 'cd_ab_load_textdomain', 7 );

require ( WP_PLUGIN_DIR . '/cd-bp-avatar-bubble/cd-ab-admin.php');
require ( WP_PLUGIN_DIR . '/cd-bp-avatar-bubble/cd-ab-cssjs.php');

/***
* BUBBLE ENGINE 
***/
add_filter('bp_core_fetch_avatar', 'cd_ab_rel_filter', 10, 2 );
function cd_ab_rel_filter( $text, $params ) {
    if ( $params['object'] == 'user') {
        return preg_replace('|<img (.+?) />|i', "<img $1 rel='{$params['item_id']}' />", $text );
    }else{
        return $text;
    }
}

function cd_ab_get_add_friend_button( $ID = false, $friend_status = false ) {
    global $bp, $friends_template;

    if ( !is_user_logged_in() )
        return false;

    if ( !$ID && $friends_template->friendship->friend )
        $ID = $friends_template->friendship->friend->id;
    else if ( !$ID && !$friends_template->friendship->friend )
        $ID = $bp->displayed_user->id;

    if ( $bp->loggedin_user->id == $ID )
        return false;

    if ( empty( $friend_status ) )
        $friend_status = friends_check_friendship_status( $bp->loggedin_user->id, $ID );

    if ('pending' == $friend_status ) {
        $button .= '<a class="requested" href="' . $bp->loggedin_user->domain . $bp->friends->slug . '/">' . __('Friendship Requested', 'buddypress') . '</a>';
    } else if ('is_friend' == $friend_status ) {
        $button .= '<a href="' . wp_nonce_url( $bp->loggedin_user->domain . $bp->friends->slug . '/remove-friend/' . $ID . '/', 'friends_remove_friend') . '" title="' . __('Cancel Friendship', 'buddypress') . '" id="friend-' . $ID . '" rel="remove" class="remove">' . __('Cancel Friendship', 'buddypress') . '</a>';
    } else {
        $button .= '<a href="' . wp_nonce_url( $bp->loggedin_user->domain . $bp->friends->slug . '/add-friend/' . $ID . '/', 'friends_add_friend') . '" title="' . __('Add Friend', 'buddypress') . '" id="friend-' . $ID . '" rel="add" class="add">' . __('Add Friend', 'buddypress') . '</a>';
    }

    return apply_filters('cd_ab_get_add_friend_button', $button );
}

/* DISPLAY EVERYTHING */
add_action('wp_ajax_the_personalinfo', 'the_personalinfo');
add_action('wp_ajax_nopriv_the_personalinfo', 'the_personalinfo');
function the_personalinfo(){
    $cd_ab = get_option('cd_ab');
    $ID = $_GET['ID'];
    if ( $cd_ab['access'] == 'admin' && is_site_admin() ) {
        get_the_personalinfo( $ID );
    }elseif ( $cd_ab['access'] == 'logged_in' && is_user_logged_in() ) {
        get_the_personalinfo( $ID );
    }elseif ( $cd_ab['access'] == 'all') {
        get_the_personalinfo( $ID );
    }else{
        echo $cd_ab['delay'].'|~|<div id="user_'.$ID.'">'.__('You don\'t have enough rights to view user data','cd_ab').'</div>';
    }
    die();
}

function get_the_personalinfo($ID) {
    global $bp;
    $cd_ab = get_option('cd_ab');
    if ( !$cd_ab['delay'] ) {
        echo '0|~|';
    }else{
        echo $cd_ab['delay'] . '|~|';
    }
    $i = 1;
    $action = 'false';
    do_action('cd_ab_before_default');
    if ( $cd_ab['messages'] == 'yes') {
        $i++;

        if ( $cd_ab['action'] == 'click') {
            $action = 'true';
            $mention = '<strong><a href="'. bp_core_get_user_domain( $ID, false, false ) .'" title="'. __('Go to profile page',  'cd_ab') .'">#</a> | </strong>';
        }
    
        if ( is_user_logged_in() ) {
            $mention .= '<strong><a href="'. bp_core_get_user_domain( $bp->loggedin_user->id, false, false ) . BP_ACTIVITY_SLUG .'/?r='.bp_core_get_username( $ID, false, false ).'" title="'. __('Mention this user', 'cd_ab') .'">@'. bp_core_get_username( $ID, false, false ) .'</a></strong>';
            $message = '<a href="'. bp_core_get_user_domain( $bp->loggedin_user->id, false, false ) . BP_MESSAGES_SLUG . '/compose/?r=' . bp_core_get_username( $ID, false, false ) .'" title="'. __('Send a private message to this user', 'cd_ab') .'">'. __('Private Message', 'cd_ab') .'</a>';
        }else{
            $mention .= '<strong><a href="' . $bp->root_domain . '/wp-login.php?redirect_to=' . urlencode( $bp->root_domain ) . '" title="'.__('You should be logged in to mention this user', 'cd_ab') .'">@'. bp_core_get_username( $ID, false, false ) .'</a></strong>';
            $message = '<strong><a href="' . $bp->root_domain . '/wp-login.php?redirect_to=' . urlencode( $bp->root_domain ) . '" title="'. __('You should be logged in to send a private message', 'cd_ab') .'">'. __('Private Message', 'cd_ab') .'</a></strong>';
        }
        $output .= '<p class="popupLine">'. $mention .' | '. $message .'</p>';
    }

    if ( $cd_ab['friend'] == 'yes' && $ID != $bp->loggedin_user->id && is_user_logged_in() ) {
        $i++;
        if ( $i != 1 ) $class = ' style="padding-top:6px;"';
        if ( $cd_ab['action'] == 'click' && $action == 'false')
            $link = '<strong><a href="'. bp_core_get_user_domain( $ID, false, false ) .'" title="'. __('Go to profile page',  'cd_ab') .'">#</a> | </strong>';
        $output .= '<p class="popupLine"'. $class .'>'. $link . cd_ab_get_add_friend_button( $ID, false) .'</p>';
    }
    do_action('cd_ab_before_fields');
    foreach ( $cd_ab as $field_id => $field_data ) {
        if ( $field_data['name'] && is_numeric( $field_id ) ) {
            $field_value = xprofile_get_field_data( $field_id, $ID );
            if ( $field_value != null ) {
                if ( $field_data['type'] == 'multiselectbox' || $field_data['type'] == 'checkbox') $field_value = bp_unserialize_profile_field ( $field_value );
                if ( $field_data['type'] == 'datebox' && $field_value != null ) $field_value = bp_format_time( bp_unserialize_profile_field ( $field_value), true );
                if ( $i != 1 ) $class = ' style="padding-top:6px;"';
                if ( $field_data['link'] == 'yes') {
                    $field_link = xprofile_filter_link_profile_data( $field_value, $field_data['type'] );
                    $field_link = apply_filters('cd_ab_field_link', $field_link, $ID, $field_id, $field_data['type'], $field_value );
                }else{
                    $field_link = $field_value;
                    $field_link = apply_filters('cd_ab_field_text', $field_link, $ID, $field_id, $field_data['type'], $field_value );
                }
                $output .= '<p class="popupLine"'. $class .'><strong>' . $field_data['name'] . '</strong>: ' . $field_link . '</p>';
            }
            $i++;
        }
    }
    $output = apply_filters('cd_ab_output', $output );
    do_action('cd_ab_after_default');
    if ( $output == '')
        $output = __('Nothing to display. Check a bit later please.', 'cd_ab');
    
    echo "<div id='user_$ID'>$output<div style='clear:both'></div></div>";
}

//
if(!defined('print_var')) {
    function print_var($var){
        echo '<pre>';
        print_r($var);
        echo '</pre>';
    }
}


?>
