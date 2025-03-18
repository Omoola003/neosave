<?php
/**
 * Plugin Name: NeoSave Wallet
 * Plugin URI: https://omoolaex.com/neosave-wallet
 * Description: A WordPress wallet plugin that enables auto-saving and seamless checkout for WooCommerce stores.
 * Version: 1.0.0
 * Author: Omoolaex
 * Author URI: https://omoolaex.com.ng
 * License: GPL-2.0+
 * Text Domain: neosave-wallet
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

// Define plugin path
if (!defined('NEOSAVE_WALLET_PATH')) {
    define('NEOSAVE_WALLET_PATH', plugin_dir_path(__FILE__));
}

// Define plugin URL
if (!defined('NEOSAVE_WALLET_URL')) {
    define('NEOSAVE_WALLET_URL', plugin_dir_url(__FILE__));
}

// Include core files
require_once NEOSAVE_WALLET_PATH . 'includes/class-main.php';
require_once NEOSAVE_WALLET_PATH . 'includes/db-schema.php';
require_once NEOSAVE_WALLET_PATH . 'includes/class-wallet.php';
require_once NEOSAVE_WALLET_PATH . 'includes/class-admin.php';
require_once NEOSAVE_WALLET_PATH . 'includes/class-reports.php';
require_once NEOSAVE_WALLET_PATH . 'includes/class-notifications.php';
require_once NEOSAVE_WALLET_PATH . 'includes/class-withdrawal.php';
require_once NEOSAVE_WALLET_PATH . 'includes/class-checkout.php';
require_once NEOSAVE_WALLET_PATH . 'includes/class-transaction-history.php';
require_once NEOSAVE_WALLET_PATH . 'includes/class-auto-save.php';
require_once NEOSAVE_WALLET_PATH . 'includes/class-api-integration.php';
require_once NEOSAVE_WALLET_PATH . 'includes/class-admin-control.php';
require_once NEOSAVE_WALLET_PATH . 'includes/class-user-roles.php';

// Activation Hook
function neosave_wallet_activate() {
    require_once NEOSAVE_WALLET_PATH . 'includes/db-schema.php';
    require_once NEOSAVE_WALLET_PATH . 'includes/class-user-roles.php';
    NeoSave_DB_Schema::install();
    NeoSave_User_Roles::add_roles();
}
register_activation_hook(__FILE__, 'neosave_wallet_activate');

// Deactivation Hook
function neosave_wallet_deactivate() {
    // Placeholder for future cleanup if needed
}
register_deactivation_hook(__FILE__, 'neosave_wallet_deactivate');

// Initialize Plugin
function neosave_wallet_init() {
    new NeoSave_Main();
    new NeoSave_Wallet();
    new NeoSave_Admin();
    new NeoSave_Reports();
    new NeoSave_Notifications();
    new NeoSave_Withdrawal();
    new NeoSave_Checkout();
    new NeoSave_Transaction_History();
    new NeoSave_Auto_Save();
    new NeoSave_API_Integration();
    new NeoSave_Admin_Control();
    new NeoSave_User_Roles();
}
add_action('plugins_loaded', 'neosave_wallet_init');
