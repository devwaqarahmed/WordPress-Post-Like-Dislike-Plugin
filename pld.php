<?php
/*
 * Plugin Name:       WP Like-dislike Post
 * Description:       Using this plugin you can like or dislike WordPress posts.
 * Author:            Waqar Ahmed
 * Author URL:        https://devwaqarahmed.github.io/portfolio/
 */

//define plugin directories
if(!defined('pg_dev_dir')){
    define('pg_dev_dir', plugin_dir_url(__FILE__));
    define('pg_dev_path', plugin_dir_path(__FILE__));
}

//plugin settings

require pg_dev_path. ('inc/settings.php');

// create table for likes and disklikes

require pg_dev_path. ('inc/db.php');

//plugin like dislike buttons

require pg_dev_path. ('inc/btns.php');
