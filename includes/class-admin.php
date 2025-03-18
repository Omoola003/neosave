<?php

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

class NeoSave_Admin {
    public function __construct() {
        add_action('admin_menu', array($this, 'register_admin_menu'));
    }

    public function register_admin_menu() {
        add_menu_page(
            __('NeoSave Wallet', 'neosave'),
            __('NeoSave Wallet', 'neosave'),
            'manage_options',
            'neosave-wallet',
            array($this, 'wallet_admin_page'),
            'dashicons-money',
            56
        );
    }

    public function wallet_admin_page() {
        echo '<div class="wrap"><h1>' . __('NeoSave Wallet Management', 'neosave') . '</h1>';
        echo '<p>' . __('Manage user balances and transactions.', 'neosave') . '</p>';
        $this->display_wallet_users_table();
        echo '</div>';
    }

    private function display_wallet_users_table() {
        $users = get_users();
        echo '<table class="wp-list-table widefat fixed striped users">';
        echo '<thead><tr><th>' . __('User', 'neosave') . '</th><th>' . __('Balance', 'neosave') . '</th></tr></thead>';
        echo '<tbody>';
        foreach ($users as $user) {
            $balance = get_user_meta($user->ID, 'neosave_wallet_balance', true);
            echo '<tr><td>' . esc_html($user->display_name) . '</td><td>' . esc_html($balance ?: 0) . '</td></tr>';
        }
        echo '</tbody></table>';
    }
}

new NeoSave_Admin();
