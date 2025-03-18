jQuery(document).ready(function ($) {
    // Fetch and display wallet balance
    function fetchWalletBalance() {
        $.ajax({
            url: neosave_ajax.ajax_url,
            type: 'POST',
            data: {
                action: 'neosave_get_wallet_balance',
                security: neosave_ajax.nonce
            },
            success: function (response) {
                if (response.success) {
                    $('#neosave-wallet-balance').text(response.data.balance);
                } else {
                    console.error(response.data.message);
                }
            }
        });
    }

    // Handle wallet deposit
    $('#neosave-deposit-form').on('submit', function (e) {
        e.preventDefault();
        let amount = $('#neosave-deposit-amount').val();

        $.ajax({
            url: neosave_ajax.ajax_url,
            type: 'POST',
            data: {
                action: 'neosave_deposit_funds',
                security: neosave_ajax.nonce,
                amount: amount
            },
            success: function (response) {
                if (response.success) {
                    alert('Deposit successful!');
                    fetchWalletBalance();
                } else {
                    alert(response.data.message);
                }
            }
        });
    });

    // Handle wallet withdrawal
    $('#neosave-withdraw-form').on('submit', function (e) {
        e.preventDefault();
        let amount = $('#neosave-withdraw-amount').val();

        $.ajax({
            url: neosave_ajax.ajax_url,
            type: 'POST',
            data: {
                action: 'neosave_withdraw_funds',
                security: neosave_ajax.nonce,
                amount: amount
            },
            success: function (response) {
                if (response.success) {
                    alert('Withdrawal successful!');
                    fetchWalletBalance();
                } else {
                    alert(response.data.message);
                }
            }
        });
    });

    // Fetch balance on page load
    fetchWalletBalance();
});