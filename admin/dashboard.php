<?php
// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

// Fetch wallet stats and data
$wallet_balance = Neosave_Wallet::get_total_wallet_balance();
$total_users = Neosave_Wallet::get_total_users();
$recent_transactions = Neosave_Wallet::get_recent_transactions(10);
?>

<div class="wrap">
    <h1><?php esc_html_e('Wallet Dashboard', 'neosave-wallet'); ?></h1>
    
    <div class="neosave-dashboard-stats">
        <div class="neosave-stat-box">
            <h3><?php esc_html_e('Total Wallet Balance', 'neosave-wallet'); ?></h3>
            <p><?php echo esc_html(number_format($wallet_balance, 2)); ?> USD</p>
        </div>
        <div class="neosave-stat-box">
            <h3><?php esc_html_e('Total Users', 'neosave-wallet'); ?></h3>
            <p><?php echo esc_html($total_users); ?></p>
        </div>
    </div>

    <h2><?php esc_html_e('Recent Transactions', 'neosave-wallet'); ?></h2>
    <table class="widefat fixed" cellspacing="0">
        <thead>
            <tr>
                <th><?php esc_html_e('User', 'neosave-wallet'); ?></th>
                <th><?php esc_html_e('Amount', 'neosave-wallet'); ?></th>
                <th><?php esc_html_e('Type', 'neosave-wallet'); ?></th>
                <th><?php esc_html_e('Date', 'neosave-wallet'); ?></th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($recent_transactions)) : ?>
                <?php foreach ($recent_transactions as $transaction) : ?>
                    <tr>
                        <td><?php echo esc_html($transaction['user_name']); ?></td>
                        <td><?php echo esc_html(number_format($transaction['amount'], 2)); ?> USD</td>
                        <td><?php echo esc_html(ucfirst($transaction['type'])); ?></td>
                        <td><?php echo esc_html(date('Y-m-d H:i', strtotime($transaction['date']))); ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php else : ?>
                <tr>
                    <td colspan="4">No recent transactions</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>
