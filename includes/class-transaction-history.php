<?php

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

class NeoSave_Transaction_History {
    public function __construct() {
        add_shortcode('neosave_transaction_history', array($this, 'render_transaction_history'));
        add_action('wp_ajax_neosave_get_transactions', array($this, 'get_transactions'));
    }

    public function render_transaction_history() {
        if (!is_user_logged_in()) {
            return '<p>You must be logged in to view your transaction history.</p>';
        }
        
        ob_start();
        ?>
        <div id="neosave-transaction-history">
            <h3>Transaction History</h3>
            <table>
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Type</th>
                        <th>Amount</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody id="neosave-transactions-list">
                    <tr><td colspan="4">Loading transactions...</td></tr>
                </tbody>
            </table>
        </div>
        <script>
            jQuery(document).ready(function($) {
                $.post('<?php echo admin_url('admin-ajax.php'); ?>', { action: 'neosave_get_transactions' }, function(response) {
                    if (response.success) {
                        let transactions = response.data;
                        let rows = '';
                        transactions.forEach(function(tx) {
                            rows += `<tr>
                                <td>${tx.date}</td>
                                <td>${tx.type}</td>
                                <td>${tx.amount}</td>
                                <td>${tx.status}</td>
                            </tr>`;
                        });
                        $('#neosave-transactions-list').html(rows);
                    } else {
                        $('#neosave-transactions-list').html('<tr><td colspan="4">No transactions found.</td></tr>');
                    }
                });
            });
        </script>
        <?php
        return ob_get_clean();
    }

    public function get_transactions() {
        $user_id = get_current_user_id();
        if (!$user_id) {
            wp_send_json_error(['message' => 'User not authenticated.']);
        }
        
        $transactions = get_user_meta($user_id, 'neosave_transaction_history', true);
        wp_send_json_success($transactions ? $transactions : []);
    }
}

new NeoSave_Transaction_History();
