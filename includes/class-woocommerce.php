<?php

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

class NeoSave_Wallet {
    public function __construct() {
        add_action('init', array($this, 'register_wallet_endpoints'));
        add_action('wp_ajax_neosave_get_wallet_balance', array($this, 'get_wallet_balance'));
        add_action('wp_ajax_neosave_deposit_funds', array($this, 'deposit_funds'));
        add_action('wp_ajax_neosave_withdraw_funds', array($this, 'withdraw_funds'));
    }

    public function register_wallet_endpoints() {
        add_rewrite_rule('neosave-wallet/?$', 'index.php?neosave_wallet=1', 'top');
    }

    public function get_wallet_balance() {
        $user_id = get_current_user_id();
        if (!$user_id) {
            wp_send_json_error(['message' => 'User not authenticated.']);
        }
        
        $balance = get_user_meta($user_id, 'neosave_wallet_balance', true);
        wp_send_json_success(['balance' => $balance ? $balance : 0]);
    }

    public function deposit_funds() {
        $user_id = get_current_user_id();
        if (!$user_id) {
            wp_send_json_error(['message' => 'User not authenticated.']);
        }
        
        $amount = isset($_POST['amount']) ? floatval($_POST['amount']) : 0;
        if ($amount <= 0) {
            wp_send_json_error(['message' => 'Invalid deposit amount.']);
        }
        
        $balance = get_user_meta($user_id, 'neosave_wallet_balance', true);
        $new_balance = $balance + $amount;
        update_user_meta($user_id, 'neosave_wallet_balance', $new_balance);
        
        wp_send_json_success(['balance' => $new_balance, 'message' => 'Funds deposited successfully.']);
    }

    public function withdraw_funds() {
        $user_id = get_current_user_id();
        if (!$user_id) {
            wp_send_json_error(['message' => 'User not authenticated.']);
        }
        
        $amount = isset($_POST['amount']) ? floatval($_POST['amount']) : 0;
        $balance = get_user_meta($user_id, 'neosave_wallet_balance', true);
        
        if ($amount <= 0 || $amount > $balance) {
            wp_send_json_error(['message' => 'Invalid withdrawal amount.']);
        }
        
        $new_balance = $balance - $amount;
        update_user_meta($user_id, 'neosave_wallet_balance', $new_balance);
        
        wp_send_json_success(['balance' => $new_balance, 'message' => 'Funds withdrawn successfully.']);
    }
}

new NeoSave_Wallet();

// WooCommerce Wallet Payment Gateway Integration
class NeoSave_Wallet_Gateway extends WC_Payment_Gateway {
    public function __construct() {
        $this->id = 'neosave_wallet';
        $this->method_title = __('NeoSave Wallet', 'neosave');
        $this->method_description = __('Allow customers to pay using their NeoSave wallet balance.', 'neosave');
        $this->has_fields = false;
        $this->init_settings();

        add_action('woocommerce_update_options_payment_gateways_' . $this->id, array($this, 'process_admin_options'));
    }

    public function process_payment($order_id) {
        $order = wc_get_order($order_id);
        $user_id = get_current_user_id();
        $wallet_balance = get_user_meta($user_id, 'neosave_wallet_balance', true);
        $order_total = $order->get_total();

        if ($wallet_balance < $order_total) {
            wc_add_notice(__('Insufficient wallet balance. Please add funds or use another payment method.', 'neosave'), 'error');
            return;
        }

        // Deduct balance and update user meta
        update_user_meta($user_id, 'neosave_wallet_balance', $wallet_balance - $order_total);
        $order->payment_complete();
        $order->reduce_order_stock();
        wc_add_notice(__('Payment successful using NeoSave Wallet!', 'neosave'));
        return array(
            'result' => 'success',
            'redirect' => $order->get_checkout_order_received_url()
        );
    }
}

function add_neosave_wallet_gateway($gateways) {
    $gateways[] = 'NeoSave_Wallet_Gateway';
    return $gateways;
}
add_filter('woocommerce_payment_gateways', 'add_neosave_wallet_gateway');
