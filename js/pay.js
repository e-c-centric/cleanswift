var paymentForm = document.getElementById('paymentForm');

paymentForm.addEventListener('submit', payWithPaystack, false);

function payWithPaystack(e) {

    e.preventDefault();
    var handler = PaystackPop.setup({
        key: 'pk_test_4884ce4a815934cd9718338e7aefcb982858cbde', // Replace with your public key
        email: document.getElementById('email-address').value,
        amount: document.getElementById('amount').value * 100, // the amount value is multiplied by 100 to convert to the lowest currency unit
        currency: 'GHS', // Use GHS for Ghana Cedis or USD for US Dollars
        ref: "" + Math.floor(Math.random() * 1000000000 + 1), // Replace with a reference you generated
        callback: function(response) {
            $.ajax({
                url: "../actions/checkout_process.php?reference=" + response.reference,// Changed URL to lowercase
                method: "GET", // Changed METHOD to lowercase
                success: function (response) {
                    window.location.href = "../customer/dashboard.php"; // Redirect to dashboard after payme
                    // paymentForm.submit();
                } // Removed extra closing parenthesis
            }); // Closed the ajax call properly
            // This happens after the payment is completed successfully
            var reference = response.reference;
            alert('Payment complete! Reference: ' + reference);
            // Make an AJAX call to your server with the reference to verify the transaction
        },
        onClose: function() {
            alert('Transaction was not completed, window closed.');
        } // Removed extra comma
    });
    handler.openIframe();
}