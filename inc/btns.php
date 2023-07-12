<?php
// Generating buttons

    function pg_like_dislike_buttons($content){
    $like_btn_label= get_option('like_btn_label');
    $dislike_btn_label= get_option('dislike_btn_label');

    $user_id = get_current_user_id();
    $post_id = get_the_ID();


    $btn_wrap="<div class='container'>";
    $like_btn = '<a href="javascript:;"  onclick="pg_like_btn_ajax('.$post_id.','.$user_id.')" class="pg_btn pg_like_btn">'.$like_btn_label.'</a>';
    $dislike_btn = '<a href="javascript:;" onclick="pg_dislikes_btn_ajax('.$post_id.','.$user_id.')" class="pg_btn pg_dislike_btn">'.$dislike_btn_label.'</a>';
    $btn_wrap_end= "</div>";

    $pg_ajax_response= "<div id='ajaxresponse' class='ajax-response'><span></span></div>";

    $content.= $btn_wrap;
    $content.= $like_btn;
    $content.= $dislike_btn;
    $content.= $btn_wrap_end;
    $content.= $pg_ajax_response;

    return $content;

}
add_filter('the_content','pg_like_dislike_buttons');