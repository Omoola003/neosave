<?php
/**
 * NeoSave Wallet - Main Class
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

class NeoSave_Main {

    public function __construct() {
        $this->includes();
        $this->init_hooks();
    }

    /**
     * Include necessary files
     */
    private function includes() {
        require_once NEOSAVE_WALLET_PATH . 'includes/class-wallet.php';
        require_once NEOSAVE_WALLET_PATH . 'includes/class-withdrawal.php';
        require_once NEOSAVE_WALLET_PATH . 'includes/class-checkout.php';
        require_once NEOSAVE_WALLET_PATH . 'includes/class-transaction-history.php';
        require_once NEOSAVE_WALLET_PATH . 'includes/class-auto-save.php';
        require_once NEOSAVE_WALLET_PATH . 'includes/class-api-integration.php';
        require_once NEOSAVE_WALLET_PATH . 'includes/class-admin-control.php';
        require_once NEOSAVE_WALLET_PATH . 'includes/class-user-roles.php';
        require_once NEOSAVE_WALLET_PATH . 'includes/class-reports.php';
        require_once NEOSAVE_WALLET_PATH . 'includes/class-notifications.php';
    }

    /**
     * Initialize Hooks
     */
    private function init_hooks() {
        add_action('init', [$this, 'register_shortcodes']);
    }

    /**
     * Register plugin shortcodes
     */
    public function register_shortcodes() {
        add_shortcode('neosave_wallet_balance', [$this, 'display_wallet_balance']);
    }

    /**
     * Display user wallet balance
     */
    public function display_wallet_balance() {
        $user_id = get_current_user_id();
        $balance = get_user_meta($user_id, 'neosave_wallet_balance', true) ?: 0;
        return '<p>Your Wallet Balance: ₦' . number_format($balance, 2) . '</p>';
    }
}

// Initialize the main class
new NeoSave_Main();
