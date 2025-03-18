<?php
/**
 * Wallet Balance Template
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

$current_user = wp_get_current_user();
$wallet_balance = get_user_meta($current_user->ID, 'neosave_wallet_balance', true);
$wallet_balance = $wallet_balance ? floatval($wallet_balance) : 0.00;
?>

<div class="neosave-wallet-balance">
    <h2><?php esc_html_e('Your Wallet Balance', 'neosave-wallet'); ?></h2>
    <p class="wallet-amount">
        <?php echo wc_price($wallet_balance); ?>
    </p>
    <a href="<?php echo esc_url(get_permalink(get_option('wallet-deposit-page'))); ?>" class="wallet-action deposit">
        <?php esc_html_e('Deposit Funds', 'neosave-wallet'); ?>
    </a>
    <a href="<?php echo esc_url(get_permalink(get_option('wallet-withdraw-page'))); ?>" class="wallet-action withdraw">
        <?php esc_html_e('Withdraw Funds', 'neosave-wallet'); ?>
    </a>
</div>
