<div id="paypal-button-container" style="width: 50px; height: 30px"></div>	
<script>
    paypal.Buttons({
    createOrder: function(data, actions) {
      console.log('Paypal createOrder data', data);
      console.log('Paypal createOrder actions', actions);
      // This function sets up the details of the transaction, including the amount and line item details.
      return actions.order.create({
        purchase_units: [{
          amount: {
            value: '0.01'
          }
        }]
      });
    },
    onApprove: function(data, actions) {
      console.log('Paypal onApprove data', data);
      console.log('Paypal onApprove actions', actions);
      return actions.order.capture().then(function(details) {
        console.log('Paypal details', details);

        alert('Transaction completed by ' + details.payer.name.given_name);
        // Call your server to save the transaction
        // return fetch('/paypal-transaction-complete', {
        //   method: 'post',
        //   headers: {
        //     'content-type': 'application/json'
        //   },
        //   body: JSON.stringify({
        //     orderID: data.orderID
        //   })
        // });
      });
    }
  }).render('#paypal-button-container');
 </script>