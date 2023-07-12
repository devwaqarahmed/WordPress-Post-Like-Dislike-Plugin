<?php
// if directly called abort!

if(!defined('WPINC')){
    die;
}
//define plugin version
if(!defined('PG_DEV_VERSION')){
    define('PG_DEV_VERSION','1.0');
}

// add scripts 
if(!function_exists('pg_dev_scripts')){
    function pg_dev_scripts(){
       
        wp_enqueue_style('pg-css', pg_dev_dir. 'assets/css/main.css');

        wp_enqueue_script('pg-likes-ajax', pg_dev_dir. 'assets/js/likes_btn_ajax.js','JQuery','1.0.0',true);

        wp_enqueue_script('pg-dislikes-ajax', pg_dev_dir. 'assets/js/dislikes_btn_ajax.js','JQuery','1.0.0',true);

        wp_enqueue_script('jquery');
        
        wp_localize_script('pg-likes-ajax','my_ajax_url', array(
            'ajax_url' => admin_url('admin-ajax.php')
        ));

        wp_localize_script('pg-dislikes-ajax','my_ajax_url', array(
            'ajax_url' => admin_url('admin-ajax.php')
        ));
    }

    add_action('wp_enqueue_scripts','pg_dev_scripts');
}

// plugin main html function
function pg_dev_html_page_setting(){
    if(!is_admin()){
        return;
    }
    ?>
        <div class="wrap">
                <h2 style="padding:10px; background:#333; color:#fff;">Post Like /disklike</h2>
                <h4>Welcome! Using this plugin you can like or dislike WordPress post and see realtime likes of current post</h4>
                <form action="options.php" method="post">
                <?php 
                    settings_fields('pg_settings'); 
                    do_settings_sections('pg_settings');
                    submit_button('Save Changes');
                      ?>
                     </form>
        </div>
        <h4><ul>
            <li>Made By : WAQAR AHMED</li>
        </ul></h4>
<?php
}


// add plugin menu in dashboard
function PG_ADD_MENU_PAGE(){
    add_menu_page('like/disklike post','Post Like/dislike','manage_options','pg_settings','pg_dev_html_page_setting','dashicons-thumbs-up',25);
}

add_action('admin_menu','PG_ADD_MENU_PAGE');

//add settings for labels

function pg_dev_plugin_setting(){
        register_setting('pg_settings', 'like_btn_label');
        register_setting('pg_settings','dislike_btn_label');

        add_settings_section('pg_setting_section','Button Labels','pg_setting_section_cb','pg_settings');

        add_settings_field( 'pg_setting_like_field', 'Like button Label', 'pg_setting_like_filed_cb', 'pg_settings', 'pg_setting_section');
        add_settings_field( 'pg_setting_dislike_field', 'DisLike button Label', 'pg_setting_dislike_filed_cb', 'pg_settings', 'pg_setting_section');
}

add_action('admin_init','pg_dev_plugin_setting');

//call back function for section

function pg_setting_section_cb(){
    echo "<h5>Define labels for like and disklike Buttons</h5>";
}
//call back function for like label field
function pg_setting_like_filed_cb(){
    //get the value of setting registerd with register_setting()
    $setting = get_option('like_btn_label');
       //output the field
    ?>
    <input type='text' name='like_btn_label' value='<?php echo isset($setting) ? esc_attr($setting): '' ;?>'>
<?php
}
//call back function for dislike label field

function pg_setting_dislike_filed_cb(){

     //get the value of setting registerd with register_setting()
    $setting = get_option('dislike_btn_label');
       //output the field
    ?>
    <input type='text' name='dislike_btn_label' value='<?php echo isset($setting) ? esc_attr($setting): '' ;?> ' >
 
    <?php

}

//Add likes to table
function pg_like_btn_ajax_action(){
    global $wpdb;
    
     require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );

     $table_name = $wpdb->prefix . "like_dislike";

     if(isset($_POST['pid']) && isset($_POST['uid'])){

     
      $user_id = $_POST['uid'];
      $post_id= $_POST['pid'];
     $check_like = $wpdb->get_var("SELECT COUNT(*) FROM $table_name WHERE user_id='$user_id'  AND post_id='$post_id' AND likes_count=1");
     

     if($check_like > 0){
        
        echo "<center>sorry , You already liked this post</center>";
     }
     else{
        $wpdb->insert(
            ''.$table_name.'',
            array(
                'post_id' => $post_id,
                'user_id' => $user_id,
                'likes_count' =>1
            ),
            array(
                '%d',
                '%d',
                '%d'
            )
        );
        if($wpdb->insert_id){
            echo "<center>Than you for liking this post!</center>";
        }

     }

    }
    wp_die();

}
add_action('wp_ajax_pg_like_btn_ajax_action','pg_like_btn_ajax_action');
add_action('wp_ajax_nopriv_pg_like_btn_ajax_action','pg_like_btn_ajax_action');


function pg_dislike_btn_ajax_action(){
    global $wpdb;
    
     require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );

     $table_name = $wpdb->prefix . "like_dislike";
     if(isset($_POST['pid']) && isset($_POST['uid'])){

     $user_id = $_POST['uid'];
     $post_id= $_POST['pid'];

     $check_like = $wpdb->get_var("SELECT COUNT(*) FROM $table_name WHERE user_id='$user_id'  AND post_id='$post_id' AND dislikes_count=1");

     if($check_like > 0){
        echo "<center>sorry , You already disliked this post! </center>";
     }
     else{
        $wpdb->insert(
            ''.$table_name.'',
            array(
                'post_id' => $post_id,
                'user_id' => $user_id,
                'dislikes_count' => 1
            ),
            array(
                '%d',
                '%d',
                '%d'
            )
        );
        if($wpdb->insert_id){
            echo "post disliked!";
        }

     }
    
    }
       
    wp_die();

}
add_action('wp_ajax_pg_dislike_btn_ajax_action','pg_dislike_btn_ajax_action');
add_action('wp_ajax_nopriv_pg_dislike_btn_ajax_action','pg_dislike_btn_ajax_action');


//function to show likes count

function show_likes_count($content){
    global $wpdb;
    
     require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );

     $table_name = $wpdb->prefix . "like_dislike";
     $post_id= get_the_ID();
     $check_like = $wpdb->get_var("SELECT COUNT(*) FROM $table_name WHERE post_id='$post_id' AND likes_count=1 ");
     $likes_result= "<center>This post is liked $check_like time(s)</center>";
     $content.= $likes_result;
     return $content;
}

add_filter('the_content','show_likes_count');
