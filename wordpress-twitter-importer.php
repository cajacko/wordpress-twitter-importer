<?php
/*
Plugin Name: Wordpress Twitter Importer
Description: Automatically importer tweets into Wordpress
Version:     1.0.0
Author:      Charlie Jackson
Author URI:  https://charliejackson.com
Text Domain: wordpress-twitter-importer
*/


require_once(__DIR__ . '/vendor/autoload.php');
use Abraham\TwitterOAuth\TwitterOAuth;

define('TWITTER_KEY', '34P4Nilgj4kDlz9cfxyoOeKKf');
define('TWITTER_SECRET', 'i6dwtgu6Ne07JaZOSKUXKkkLnmi5vkkHpuYFvgeGhzDDCqJeo7');

function temp()
{
    get_tweets();
}

add_action('init', 'temp');

function get_tweets()
{
    $connection = new TwitterOAuth(TWITTER_KEY, TWITTER_SECRET);
    $statuses = $connection->get("statuses/user_timeline", [
        "screen_name" => "charliejackson",
        'exclude_replies' => true,
        'contributor_details' => true
    ]);

    print_r($statuses);
}
