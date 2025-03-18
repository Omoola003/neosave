<?php
/**
 * Wallet Withdraw Template
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

$current_user_id = get_current_user_id();
$user_balance = Neosave_Wallet::get_user_balance($current_user_id);

?>

<div class="neosave-wallet-withdraw">
    <h2><?php esc_html_e('Withdraw Funds', 'neosave-wallet'); ?></h2>
    <p><?php esc_html_e('Available Balance:', 'neosave-wallet'); ?> <strong><?php echo wc_price($user_balance); ?></strong></p>

    <form id="neosave-withdraw-form" method="post">
        <label for="withdraw-amount"> <?php esc_html_e('Withdrawal Amount:', 'neosave-wallet'); ?> </label>
        <input type="number" id="withdraw-amount" name="withdraw_amount" min="1" max="<?php echo esc_attr($user_balance); ?>" required>

        <label for="withdraw-method"> <?php esc_html_e('Withdrawal Method:', 'neosave-wallet'); ?> </label>
        <select id="withdraw-method" name="withdraw_method" required>
            <option value="bank_transfer"> <?php esc_html_e('Bank Transfer', 'neosave-wallet'); ?> </option>
            <option value="paypal"> <?php esc_html_e('PayPal', 'neosave-wallet'); ?> </option>
        </select>

        <button type="submit" class="button button-primary"> <?php esc_html_e('Request Withdrawal', 'neosave-wallet'); ?> </button>
    </form>

    <div id="neosave-withdraw-response"></div>
</div>

<script>
jQuery(document).ready(function($) {
    $('#neosave-withdraw-form').on('submit', function(e) {
        e.preventDefault();
        let formData = $(this).serialize();

        $.ajax({
            type: 'POST',
            url: neosave_ajax.ajax_url,
            data: formData + '&action=neosave_withdraw',
            beforeSend: function() {
                $('#neosave-withdraw-response').html('<p>Processing...</p>');
            },
            success: function(response) {
                $('#neosave-withdraw-response').html('<p>' + response.message + '</p>');
                if (response.success) {
                    $('#neosave-withdraw-form')[0].reset();
                }
            },
            error: function() {
                $('#neosave-withdraw-response').html('<p>An error occurred. Please try again.</p>');
            }
        });
    });
});
</script>
