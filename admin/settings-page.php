<?php
/**
 * Admin Settings Page
 */

// Exit if accessed directly.
if (!defined('ABSPATH')) {
    exit;
}

class Neosave_Wallet_Settings {
    public static function render_settings_page() {
        ?>
        <div class="wrap">
            <h1><?php esc_html_e('NeoSave Wallet Settings', 'neosave-wallet'); ?></h1>
            <form method="post" action="options.php">
                <?php
                settings_fields('neosave_wallet_settings_group');
                do_settings_sections('neosave_wallet_settings');
                submit_button();
                ?>
            </form>
        </div>
        <?php
    }
}

function neosave_wallet_register_settings() {
    register_setting('neosave_wallet_settings_group', 'neosave_wallet_options');
    
    add_settings_section(
        'neosave_wallet_main_settings',
        __('Main Settings', 'neosave-wallet'),
        '__return_false',
        'neosave_wallet_settings'
    );
    
    add_settings_field(
        'default_balance',
        __('Default Wallet Balance', 'neosave-wallet'),
        'neosave_wallet_default_balance_callback',
        'neosave_wallet_settings',
        'neosave_wallet_main_settings'
    );
}
add_action('admin_init', 'neosave_wallet_register_settings');

function neosave_wallet_default_balance_callback() {
    $options = get_option('neosave_wallet_options');
    ?>
    <input type="number" name="neosave_wallet_options[default_balance]" value="<?php echo isset($options['default_balance']) ? esc_attr($options['default_balance']) : ''; ?>" />
    <p class="description">Set the default balance for new users.</p>
    <?php
}

function neosave_wallet_add_settings_menu() {
    add_menu_page(
        __('NeoSave Wallet', 'neosave-wallet'),
        __('NeoSave Wallet', 'neosave-wallet'),
        'manage_options',
        'neosave_wallet_settings',
        ['Neosave_Wallet_Settings', 'render_settings_page'],
        'dashicons-money',
        90
    );
}
add_action('admin_menu', 'neosave_wallet_add_settings_menu');
