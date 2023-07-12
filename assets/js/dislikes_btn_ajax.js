function pg_dislikes_btn_ajax(postId, usrid) {

  var post_id = postId;
  var user_ID = usrid;

  jQuery.ajax({
    url: my_ajax_url.ajax_url,
    type: 'post',
    data: {
      action: 'pg_dislike_btn_ajax_action',
      pid: post_id,
      uid: user_ID
    },
    success: function (response) {
      jQuery("#ajaxresponse span").html(response);

    }
  });

}
