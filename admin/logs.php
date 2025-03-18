<?php
// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

// Fetch logs from the database
$logs = Neosave_Wallet_Helper::get_wallet_logs();
?>

<div class="wrap">
    <h1 class="wp-heading-inline">Wallet Logs</h1>
    <hr class="wp-header-end">
    
    <table class="widefat fixed striped">
        <thead>
            <tr>
                <th>ID</th>
                <th>User</th>
                <th>Action</th>
                <th>Amount</th>
                <th>Transaction ID</th>
                <th>Date</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($logs)) : ?>
                <?php foreach ($logs as $log) : ?>
                    <tr>
                        <td><?php echo esc_html($log->id); ?></td>
                        <td><?php echo esc_html(get_userdata($log->user_id)->display_name); ?></td>
                        <td><?php echo esc_html($log->action); ?></td>
                        <td><?php echo esc_html($log->amount); ?></td>
                        <td><?php echo esc_html($log->transaction_id); ?></td>
                        <td><?php echo esc_html($log->created_at); ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php else : ?>
                <tr>
                    <td colspan="6">No logs found.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>
