<div id="paypal-button-container" style="width: 50px; height: 30px"></div>	
<script>
    paypal.Buttons({
      createOrder: function(data, actions) {
        return actions.order.create({
          purchase_units: [{
            amount: {
              value: '0.1'
            }
          }]
        });
      },
      onApprove: function(data, actions) {
        return actions.order.capture().then(function(details) {
          if (details.status == "COMPLETED") {
            swal("Payment success.", "Payment ID: " + details.id, "success");
          } else {
            swal("Payment fail.", "Payment ID: " + details.id, "warning");
          }
          
        });
      }
    }).render('#paypal-button-container');
 </script>