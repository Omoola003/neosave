<?php

namespace Neosave_Wallet\API;

use WP_REST_Request;
use WP_REST_Response;
use WP_Error;
use Neosave_Wallet\Includes\Class_Wallet;
use Neosave_Wallet\Includes\Class_Transaction_History;

class Controller {
    
    public static function handle_deposit(WP_REST_Request $request) {
        $user_id = get_current_user_id();
        $amount = $request->get_param('amount');

        if (!$user_id || !$amount || !is_numeric($amount) || $amount <= 0) {
            return new WP_Error('invalid_request', __('Invalid deposit request', 'neosave-wallet'), ['status' => 400]);
        }

        $wallet = new Class_Wallet($user_id);
        $new_balance = $wallet->deposit($amount);

        Class_Transaction_History::log_transaction($user_id, $amount, 'deposit');

        return new WP_REST_Response(['message' => __('Deposit successful', 'neosave-wallet'), 'balance' => $new_balance], 200);
    }

    public static function handle_withdraw(WP_REST_Request $request) {
        $user_id = get_current_user_id();
        $amount = $request->get_param('amount');

        if (!$user_id || !$amount || !is_numeric($amount) || $amount <= 0) {
            return new WP_Error('invalid_request', __('Invalid withdrawal request', 'neosave-wallet'), ['status' => 400]);
        }

        $wallet = new Class_Wallet($user_id);
        $result = $wallet->withdraw($amount);

        if (is_wp_error($result)) {
            return $result;
        }

        Class_Transaction_History::log_transaction($user_id, $amount, 'withdrawal');

        return new WP_REST_Response(['message' => __('Withdrawal successful', 'neosave-wallet'), 'balance' => $result], 200);
    }

    public static function get_transaction_history(WP_REST_Request $request) {
        $user_id = get_current_user_id();
        
        if (!$user_id) {
            return new WP_Error('unauthorized', __('User not authenticated', 'neosave-wallet'), ['status' => 403]);
        }

        $transactions = Class_Transaction_History::get_transactions($user_id);
        return new WP_REST_Response(['transactions' => $transactions], 200);
    }
}
