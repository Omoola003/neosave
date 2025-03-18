<?php
/**
 * Transaction History Template
 */
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

$current_user = wp_get_current_user();
$transactions = Neosave_Wallet::get_user_transactions($current_user->ID);
?>

<div class="neosave-wallet-transaction-history">
    <h2><?php esc_html_e('Transaction History', 'neosave-wallet'); ?></h2>
    
    <?php if (!empty($transactions)) : ?>
        <table class="neosave-wallet-table">
            <thead>
                <tr>
                    <th><?php esc_html_e('Transaction ID', 'neosave-wallet'); ?></th>
                    <th><?php esc_html_e('Type', 'neosave-wallet'); ?></th>
                    <th><?php esc_html_e('Amount', 'neosave-wallet'); ?></th>
                    <th><?php esc_html_e('Status', 'neosave-wallet'); ?></th>
                    <th><?php esc_html_e('Date', 'neosave-wallet'); ?></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($transactions as $transaction) : ?>
                    <tr>
                        <td><?php echo esc_html($transaction->transaction_id); ?></td>
                        <td><?php echo esc_html(ucfirst($transaction->type)); ?></td>
                        <td><?php echo esc_html(wc_price($transaction->amount)); ?></td>
                        <td><?php echo esc_html(ucfirst($transaction->status)); ?></td>
                        <td><?php echo esc_html(date_i18n(get_option('date_format'), strtotime($transaction->date_created))); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else : ?>
        <p><?php esc_html_e('No transactions found.', 'neosave-wallet'); ?></p>
    <?php endif; ?>
</div>
