<?php

add_action('admin_menu', 'plugin_admin_add_page');

function plugin_admin_add_page()
{
    add_options_page(WTI_PLUGIN_NAME, WTI_PLUGIN_NAME, 'manage_options', WTI_PLUGIN_ID, 'wti_options_page');
}

function wti_options_page()
{
    ?>
    <div>
        <form action="options.php" method="post">
            <?php settings_fields(WTI_OPTIONS_SLUG); ?>
            <?php do_settings_sections(WTI_PLUGIN_ID); ?>
             
            <input name="Submit" type="submit" value="<?php esc_attr_e('Save Changes'); ?>" />
        </form>

        <h2>Actions</h2>

        <form action="" method="POST">
            <input type="hidden" name="<?php echo WTI_ACTION_LATEST; ?>" value="true" />
            <input type="submit" value="<?php esc_attr_e('Get Latest Tweets'); ?>" />
        </form>

        <form action="" method="POST">
            <input type="hidden" name="<?php echo WTI_ACTION_OLDER; ?>" value="true" />
            <input type="submit" value="<?php esc_attr_e('Get Older Tweets'); ?>" />
        </form>
    </div>
    <?php
}

add_action('admin_init', 'wti_admin_init');

function wti_admin_init()
{
    register_setting(WTI_OPTIONS_SLUG, WTI_OPTIONS_SLUG, 'wti_options_validate');
    add_settings_section(WTI_OPTIONS_SECTION, WTI_PLUGIN_NAME, 'wti_section_text', WTI_PLUGIN_ID);
    add_settings_field(WTI_APP_KEY_ID, 'App Key', 'wti_key_setting_string', WTI_PLUGIN_ID, WTI_OPTIONS_SECTION);
    add_settings_field(WTI_APP_SECRET_ID, 'App Secret', 'wti_secret_setting_string', WTI_PLUGIN_ID, WTI_OPTIONS_SECTION);
    add_settings_field(WTI_TWITTER_QUERY, 'Twitter username', 'wti_twitter_query_setting_string', WTI_PLUGIN_ID, WTI_OPTIONS_SECTION);
}

function wti_section_text()
{
    echo '<p>Add in your Twitter keys here.</p>';
}

function wti_key_setting_string()
{
    $options = get_option(WTI_OPTIONS_SLUG);
    echo "<input id='" . WTI_APP_KEY_ID . "' name='" . WTI_OPTIONS_SLUG . "[" . WTI_APP_KEY_ID . "]' size='40' type='text' value='{$options[WTI_APP_KEY_ID]}' />";
}

function wti_secret_setting_string()
{
    $options = get_option(WTI_OPTIONS_SLUG);
    echo "<input id='" . WTI_APP_SECRET_ID . "' name='" . WTI_OPTIONS_SLUG . "[" . WTI_APP_SECRET_ID . "]' size='40' type='text' value='{$options[WTI_APP_SECRET_ID]}' />";
}

function wti_twitter_query_setting_string()
{
    $options = get_option(WTI_OPTIONS_SLUG);
    echo "<input id='" . WTI_TWITTER_QUERY . "' name='" . WTI_OPTIONS_SLUG . "[" . WTI_TWITTER_QUERY . "]' size='40' type='text' value='{$options[WTI_TWITTER_QUERY]}' />";
}

function wti_options_validate($input)
{
    return $input;
}

function wti_get_keys()
{
    $options = get_option(WTI_OPTIONS_SLUG);

    if (!isset($options[WTI_APP_KEY_ID])) {
        return false;
    }

    if (!isset($options[WTI_APP_SECRET_ID])) {
        return false;
    }

    if (!isset($options[WTI_TWITTER_QUERY])) {
        return false;
    }

    if (strlen($options[WTI_APP_KEY_ID]) < 5) {
        return false;
    }

    if (strlen($options[WTI_APP_SECRET_ID]) < 5) {
        return false;
    }

    if (strlen($options[WTI_TWITTER_QUERY]) < 5) {
        return false;
    }

    return $options;
}
