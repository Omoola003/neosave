<?php
// Security check
defined('ABSPATH') || exit;

// Fetch transactions from the database
$transactions = get_transactions_from_db(); // Assume this function fetches transactions
$total_transactions = count($transactions);
$per_page = 10;
$total_pages = ceil($total_transactions / $per_page);
$current_page = isset($_GET['paged']) ? max(1, $_GET['paged']) : 1;
$offset = ($current_page - 1) * $per_page;
$paginated_transactions = array_slice($transactions, $offset, $per_page);

?>

<div class="wrap">
    <h1 class="wp-heading-inline">Wallet Transactions</h1>
    <hr class="wp-header-end">
    
    <form method="get">
        <input type="hidden" name="page" value="wallet_transactions">
        <input type="text" name="search" placeholder="Search Transactions...">
        <select name="status">
            <option value="">All</option>
            <option value="completed">Completed</option>
            <option value="pending">Pending</option>
            <option value="failed">Failed</option>
        </select>
        <button type="submit" class="button button-primary">Filter</button>
    </form>
    
    <table class="wp-list-table widefat fixed striped">
        <thead>
            <tr>
                <th>ID</th>
                <th>User</th>
                <th>Type</th>
                <th>Amount</th>
                <th>Status</th>
                <th>Date</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($paginated_transactions)): ?>
                <?php foreach ($paginated_transactions as $transaction): ?>
                    <tr>
                        <td><?php echo esc_html($transaction['id']); ?></td>
                        <td><?php echo esc_html($transaction['user_name']); ?></td>
                        <td><?php echo esc_html($transaction['type']); ?></td>
                        <td><?php echo esc_html($transaction['amount']); ?></td>
                        <td><?php echo esc_html($transaction['status']); ?></td>
                        <td><?php echo esc_html($transaction['date']); ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="6">No transactions found.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
    
    <div class="pagination">
        <?php
        for ($i = 1; $i <= $total_pages; $i++) {
            echo '<a href="?page=wallet_transactions&paged=' . $i . '" class="button ' . ($i == $current_page ? 'button-primary' : '') . '">' . $i . '</a> ';
        }
        ?>
    </div>
</div>
