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

        $this->log_transaction($user_id, 'deposit', $amount);
        
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

        $this->log_transaction($user_id, 'withdrawal', $amount);
        
        wp_send_json_success(['balance' => $new_balance, 'message' => 'Funds withdrawn successfully.']);
    }

    private function log_transaction($user_id, $type, $amount) {
        $transactions = get_user_meta($user_id, 'neosave_wallet_transactions', true);
        if (!is_array($transactions)) {
            $transactions = [];
        }

        $transactions[] = [
            'type' => $type,
            'amount' => $amount,
            'date' => current_time('mysql'),
        ];

        update_user_meta($user_id, 'neosave_wallet_transactions', $transactions);
    }
}

new NeoSave_Wallet();
