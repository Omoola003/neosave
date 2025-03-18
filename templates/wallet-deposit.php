<?php
/**
 * Wallet Deposit Template
 */
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}
?>

<div class="neosave-wallet-deposit">
    <h2><?php esc_html_e('Deposit Funds', 'neosave-wallet'); ?></h2>
    <form id="neosave-deposit-form">
        <label for="deposit-amount"><?php esc_html_e('Enter Amount', 'neosave-wallet'); ?>:</label>
        <input type="number" id="deposit-amount" name="deposit_amount" min="1" required>
        
        <label for="payment-method"><?php esc_html_e('Select Payment Method', 'neosave-wallet'); ?>:</label>
        <select id="payment-method" name="payment_method" required>
            <option value="paypal">PayPal</option>
            <option value="stripe">Stripe</option>
            <option value="bank">Bank Transfer</option>
        </select>
        
        <button type="submit" class="neosave-deposit-button">
            <?php esc_html_e('Deposit Now', 'neosave-wallet'); ?>
        </button>
    </form>
    
    <div id="neosave-deposit-message"></div>
</div>

<script>
document.getElementById('neosave-deposit-form').addEventListener('submit', function(event) {
    event.preventDefault();
    
    let amount = document.getElementById('deposit-amount').value;
    let method = document.getElementById('payment-method').value;
    
    let formData = new FormData();
    formData.append('action', 'neosave_wallet_deposit');
    formData.append('deposit_amount', amount);
    formData.append('payment_method', method);
    
    fetch('<?php echo admin_url('admin-ajax.php'); ?>', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        document.getElementById('neosave-deposit-message').innerHTML = data.message;
    });
});
</script>
