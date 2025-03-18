<?php
// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

// Fetch all users with wallet balances
$users = get_users();
?>

<div class="wrap">
    <h1 class="wp-heading-inline">Wallet Users</h1>
    <table class="wp-list-table widefat fixed striped">
        <thead>
            <tr>
                <th scope="col">User ID</th>
                <th scope="col">Name</th>
                <th scope="col">Email</th>
                <th scope="col">Wallet Balance</th>
                <th scope="col">Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($users as $user) :
                $user_id = $user->ID;
                $wallet_balance = get_user_meta($user_id, '_neosave_wallet_balance', true) ?: '0.00';
            ?>
                <tr>
                    <td><?php echo esc_html($user_id); ?></td>
                    <td><?php echo esc_html($user->display_name); ?></td>
                    <td><?php echo esc_html($user->user_email); ?></td>
                    <td><?php echo esc_html(number_format($wallet_balance, 2)); ?> USD</td>
                    <td>
                        <a href="admin.php?page=neosave_edit_user&user_id=<?php echo esc_attr($user_id); ?>" class="button">Manage</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
