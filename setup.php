<?php

add_action('init', 'wti_create_post_type');

function wti_create_post_type()
{
    register_post_type(
        WTI_POST_TYPE,
        array(
            'labels' => array(
                'name' => __('Tweets'),
                'singular_name' => __('Tweet')
            ),
            'public' => true,
            'has_archive' => true,
            'taxonomies' => array('category', 'post_tag'),
        )
    );
}

add_action('init', 'wti_create_hashtag_tax');

function wti_create_hashtag_tax()
{
    register_taxonomy(
        WTI_HASHTAG_TAX,
        WTI_POST_TYPE,
        array(
            'label' => __('Hashtags'),
            'hierarchical' => false,
        )
    );
}

add_filter('cron_schedules', 'wti_add_cron_schedule');
 
function wti_add_cron_schedule($schedules)
{
    if (!isset($schedules[WTI_SCHEDULE])) {
        $schedules[WTI_SCHEDULE] = array(
            'interval' => 300,
            'display'  => esc_html__('Every Five Seconds'),
        );
    }
 
    return $schedules;
}
