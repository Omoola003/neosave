<?php
/**
 * Uninstall script for NeoSave Wallet Plugin
 *
 * This file is executed when the plugin is deleted from the WordPress admin panel.
 * It removes all plugin settings and custom database tables but preserves user wallet balances.
 */

if (!defined('WP_UNINSTALL_PLUGIN')) {
    exit;
}

// Global WP Database
global $wpdb;

// Define plugin options and table names
$transactions_table = $wpdb->prefix . 'neosave_transactions';
$options = [
    'neosave_wallet_settings',
    'neosave_wallet_version',
];

// Preserve user wallet balances but remove transactions and settings
$wpdb->query("DROP TABLE IF EXISTS {$transactions_table}");

// Delete plugin options
foreach ($options as $option) {
    delete_option($option);
    delete_site_option($option);
}

// Remove transients related to the plugin
$wpdb->query("DELETE FROM {$wpdb->options} WHERE option_name LIKE '_transient_neosave_%' OR option_name LIKE '_transient_timeout_neosave_%'");

// Log the uninstallation process
$log_file = WP_CONTENT_DIR . '/neosave-uninstall-log.txt';
$log_message = "[" . date('Y-m-d H:i:s') . "] NeoSave Wallet Plugin uninstalled. Transactions and settings removed, wallet balances preserved.\n";

file_put_contents($log_file, $log_message, FILE_APPEND);

?>
