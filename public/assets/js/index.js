paypal.Button.render({
    env: 'production', // Or 'production'
    commit: true, // Show a 'Pay Now' button

    style: {
        color: 'blue',
        size: 'responsive',
        shape: 'rect',
        label: 'pay',
        tagline: false
    },
    // Set up the payment:
    // 1. Add a payment callback
    payment: function(data, actions) {
        // 2. Make a request to your server
        if(typeof buyOne !== "undefined" && buyOne === true) {
            return actions.request.post(payer)
        }

        return actions.request.post('/newPay'+newPay)

    .then(function(res) {
            // 3. Return res.id from the response
            return res;
        });
    },
    // Execute the payment:
    // 1. Add an onAuthorize callback
    onAuthorize: function(data, actions) {
        // 2. Make a request to your server
        if(typeof buyOne !== "undefined" && buyOne === true) {
            return actions.request.post(auth, {
                paymentID: data.paymentID,
                payerID:   data.payerID
            })
          .then(function(res) {
              // 3. Show the buyer a confirmation message.
              window.location.replace(res);
          });
        }
        return actions.request.post('/process', {
            token: data.orderID,
            payerID:   data.payerID
        })
    .then(function(res) {
            // 3. Show the buyer a confirmation message.
            window.location.replace(res);
        });
    }
}, '#paypal-button');
