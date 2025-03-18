<?php

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

class NeoSave_API {
    public function __construct() {
        add_action('rest_api_init', array($this, 'register_api_endpoints'));
    }

    public function register_api_endpoints() {
        register_rest_route('neosave/v1', '/wallet/balance', array(
            'methods' => 'GET',
            'callback' => array($this, 'get_wallet_balance'),
            'permission_callback' => array($this, 'verify_user_authentication')
        ));

        register_rest_route('neosave/v1', '/wallet/deposit', array(
            'methods' => 'POST',
            'callback' => array($this, 'deposit_funds'),
            'permission_callback' => array($this, 'verify_user_authentication')
        ));

        register_rest_route('neosave/v1', '/wallet/withdraw', array(
            'methods' => 'POST',
            'callback' => array($this, 'withdraw_funds'),
            'permission_callback' => array($this, 'verify_user_authentication')
        ));

        register_rest_route('neosave/v1', '/wallet/transactions', array(
            'methods' => 'GET',
            'callback' => array($this, 'get_transaction_history'),
            'permission_callback' => array($this, 'verify_user_authentication')
        ));
    }

    public function get_wallet_balance(WP_REST_Request $request) {
        $user_id = get_current_user_id();
        $balance = get_user_meta($user_id, 'neosave_wallet_balance', true);
        return rest_ensure_response(['balance' => $balance ? $balance : 0]);
    }

    public function deposit_funds(WP_REST_Request $request) {
        $user_id = get_current_user_id();
        $amount = floatval($request->get_param('amount'));
        if ($amount <= 0) {
            return new WP_Error('invalid_amount', 'Invalid deposit amount', ['status' => 400]);
        }
        $balance = get_user_meta($user_id, 'neosave_wallet_balance', true);
        $new_balance = $balance + $amount;
        update_user_meta($user_id, 'neosave_wallet_balance', $new_balance);
        return rest_ensure_response(['message' => 'Funds deposited successfully', 'balance' => $new_balance]);
    }

    public function withdraw_funds(WP_REST_Request $request) {
        $user_id = get_current_user_id();
        $amount = floatval($request->get_param('amount'));
        $balance = get_user_meta($user_id, 'neosave_wallet_balance', true);
        if ($amount <= 0 || $amount > $balance) {
            return new WP_Error('invalid_withdrawal', 'Invalid withdrawal amount', ['status' => 400]);
        }
        $new_balance = $balance - $amount;
        update_user_meta($user_id, 'neosave_wallet_balance', $new_balance);
        return rest_ensure_response(['message' => 'Funds withdrawn successfully', 'balance' => $new_balance]);
    }

    public function get_transaction_history(WP_REST_Request $request) {
        $user_id = get_current_user_id();
        $transactions = get_user_meta($user_id, 'neosave_wallet_transactions', true);
        return rest_ensure_response(['transactions' => is_array($transactions) ? $transactions : []]);
    }

    public function verify_user_authentication() {
        return is_user_logged_in();
    }
}

new NeoSave_API();
