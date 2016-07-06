<?php

require_once(WTI_PLUGIN_PATH . 'vendor/autoload.php');
use Abraham\TwitterOAuth\TwitterOAuth;

if (!wp_next_scheduled(WTI_CRON)) {
    wp_schedule_event(time(), WTI_SCHEDULE, WTI_CRON);
}

add_action(WTI_CRON, 'wti_cron');

function wti_cron()
{
    wti_get_tweets();
}

function wti_run_action()
{
    if (!is_user_logged_in()) {
        return false;
    }

    if (isset($_POST[WTI_ACTION_LATEST])) {
        wti_get_tweets();
    }

    if (isset($_POST[WTI_ACTION_OLDER])) {
        wti_get_tweets(false);
    }
}

add_action('init', 'wti_run_action');

function wti_get_tweet_id($last = true)
{
    $args = array(
        'post_type' => WTI_POST_TYPE,
        'post_status' => 'any',
        'orderby' => 'meta_value_num',
        'meta_key' => WTI_META_TWEET_ID,
    );

    if ($last) {
        $args['order'] = 'DESC';
    } else {
        $args['order'] = 'ASC';
    }

    $posts = get_posts($args);

    if (count($posts) === 0) {
        return false;
    } else {
        $post_id = $posts[0]->ID;
        $tweet_id = get_post_meta($post_id, WTI_META_TWEET_ID, true);
        return $tweet_id;
    }
}

function wti_get_tweets($last = true)
{
    $options = wti_get_keys();

    if (!$options) {
        return false;
    }

    $connection = new TwitterOAuth($options[WTI_APP_KEY_ID], $options[WTI_APP_SECRET_ID]);

    $args = array(
        "screen_name" => $options[WTI_TWITTER_QUERY],
        'exclude_replies' => true,
        'contributor_details' => true,
        'count' => 200
    );

    if ($id = wti_get_tweet_id($last)) {
        if ($last) {
            $args['since_id'] = $id;
        } else {
            $args['max_id'] = $id;
        }
    }

    $tweets = $connection->get("statuses/user_timeline", $args);

    if (!is_array($tweets)) {
        return false;
    }

    if (count($tweets) === 0) {
        return true;
    }

    if (!isset($tweets[0]->id)) {
        return false;
    }

    return wti_save_tweets($tweets);
}

function wti_does_tweet_id_exist($tweet_id)
{
    $args = array(
        'meta_key' => WTI_META_TWEET_ID,
        'meta_value' => $tweet_id,
        'post_type' => WTI_POST_TYPE,
        'post_status' => 'any',
    );

    $posts = get_posts($args);

    if (count($posts) === 0) {
        return false;
    } else {
        return $posts[0]->ID;
    }
}

function wti_save_tweets($tweets)
{
    foreach ($tweets as $tweet) {
        $post_data = array(
            'post_status' => 'publish',
            'post_type' => WTI_POST_TYPE,
        );

        $tweet_id = $tweet->id;
        $post_data['meta_input'][WTI_META_TWEET_ID] = $tweet_id;

        if ($post_id = wti_does_tweet_id_exist($tweet_id)) {
            continue;
        }

        $tweet_date = $tweet->created_at;
        $tweet_date = date('Y-m-d H:i:s', strtotime($tweet_date));
        $post_data['post_date'] = $tweet_date;

        $tweet_text = $tweet->text;
        $post_data['post_content'] = $tweet_text;
        $post_data['post_title'] = $tweet_text;

        if (isset($tweet->entities->hashtags)) {
            $hashtags = array();

            foreach ($tweet->entities->hashtags as $hashtag) {
                $hashtags[] = $hashtag->text;
            }

            if (count($hashtags)) {
                $post_data['tax_input'][WTI_HASHTAG_TAX] = $hashtags;
                $post_data['tax_input']['post_tag'] = $hashtags;
            }
        }

        $tweet_object = serialize($tweet);
        $post_data['meta_input']['tweet'] = $tweet_object;

        $post_id = wp_insert_post($post_data);
    }
}
