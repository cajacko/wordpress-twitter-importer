<?php
/*
Plugin Name: Wordpress Twitter Importer
Description: Automatically importer tweets into Wordpress
Version:     1.0.0
Author:      Charlie Jackson
Author URI:  https://charliejackson.com
Text Domain: wordpress-twitter-importer
*/

define('WTI_APP_KEY_ID', 'wti_app_key');
define('WTI_APP_SECRET_ID', 'wti_app_secret');
define('WTI_PLUGIN_ID', 'wordpress_twitter_importer');
define('WTI_PLUGIN_NAME', 'Wordpress Twitter Importer');
define('WTI_OPTIONS_SECTION', 'wti_main');
define('WTI_OPTIONS_SLUG', 'wti_options');
define('WTI_PLUGIN_PATH', plugin_dir_path(__FILE__));
define('WTI_PLUGIN_URL', plugin_dir_url(__FILE__));
define('WTI_POST_TYPE', 'tweet');
define('WTI_HASHTAG_TAX', 'twitter_hashtag');
define('WTI_META_TWEET_ID', 'tweet_id');
define('WTI_CRON', 'wti_get_tweets');
define('WTI_ACTION_LATEST', 'wti-get-latest-tweets');
define('WTI_ACTION_OLDER', 'wti-get-older-tweets');
define('WTI_SCHEDULE', 'five_minutes');

require_once(WTI_PLUGIN_PATH .'setup.php');
require_once(WTI_PLUGIN_PATH .'options.php');
require_once(WTI_PLUGIN_PATH .'process.php');
