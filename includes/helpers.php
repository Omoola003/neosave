<?php

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

/**
 * Get user wallet balance.
 *
 * @param int $user_id User ID.
 * @return float Wallet balance.
 */
function neosave_get_wallet_balance($user_id) {
    $balance = get_user_meta($user_id, 'neosave_wallet_balance', true);
    return $balance ? floatval($balance) : 0.00;
}

/**
 * Update user wallet balance.
 *
 * @param int $user_id User ID.
 * @param float $amount Amount to set.
 */
function neosave_update_wallet_balance($user_id, $amount) {
    update_user_meta($user_id, 'neosave_wallet_balance', floatval($amount));
}

/**
 * Log wallet transactions.
 *
 * @param int $user_id User ID.
 * @param string $type Transaction type (deposit, withdrawal, payment).
 * @param float $amount Transaction amount.
 */
function neosave_log_transaction($user_id, $type, $amount) {
    $transactions = get_user_meta($user_id, 'neosave_wallet_transactions', true);
    if (!is_array($transactions)) {
        $transactions = [];
    }

    $transactions[] = [
        'type'   => $type,
        'amount' => floatval($amount),
        'date'   => current_time('mysql'),
    ];

    update_user_meta($user_id, 'neosave_wallet_transactions', $transactions);
}

/**
 * Format amount as currency.
 *
 * @param float $amount Amount to format.
 * @return string Formatted currency.
 */
function neosave_format_currency($amount) {
    return wc_price(floatval($amount));
}

/**
 * Log errors for debugging.
 *
 * @param string $message Error message.
 */
function neosave_log_error($message) {
    error_log("[NeoSave Wallet] " . $message);
}

/**
 * Check if user has sufficient balance.
 *
 * @param int $user_id User ID.
 * @param float $amount Amount to check.
 * @return bool True if balance is sufficient, false otherwise.
 */
function neosave_has_sufficient_balance($user_id, $amount) {
    $balance = neosave_get_wallet_balance($user_id);
    return $balance >= floatval($amount);
}

