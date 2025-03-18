jQuery(document).ready(function ($) {
    console.log("NeoSave Wallet Admin Script Loaded");

    // Handle balance adjustment
    $(document).on("click", ".neosave-adjust-balance", function () {
        let userId = $(this).data("user-id");
        let adjustment = parseFloat($("#neosave-balance-" + userId).val());
        let actionType = $(this).data("action");

        if (isNaN(adjustment) || adjustment === 0) {
            alert("Please enter a valid amount.");
            return;
        }

        let data = {
            action: "neosave_admin_adjust_balance",
            user_id: userId,
            adjustment: adjustment,
            type: actionType,
            security: neosave_admin_nonce,
        };

        $.post(ajaxurl, data, function (response) {
            if (response.success) {
                alert("Balance updated successfully!");
                location.reload();
            } else {
                alert(response.data.message);
            }
        });
    });

    // Handle transaction log fetching
    $(".neosave-view-transactions").on("click", function () {
        let userId = $(this).data("user-id");
        let data = {
            action: "neosave_get_user_transactions",
            user_id: userId,
            security: neosave_admin_nonce,
        };

        $.post(ajaxurl, data, function (response) {
            if (response.success) {
                $("#neosave-transactions-modal .modal-body").html(response.data);
                $("#neosave-transactions-modal").modal("show");
            } else {
                alert("Failed to load transactions.");
            }
        });
    });
});
