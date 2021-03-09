<?php
$charge = json_decode($order->payment_data, true);
?>
<div class="section-md">
    <div class="container container-wide" style="padding-bottom: 40px; padding-top: 40px">
        <div class="col-md-7 mx-auto">
            <div class="card card-summary">
                <h5 class="card-header text-uppercase" style="color: #ff6129">THANK YOU</h5>
                <div class="card-body">
                    <div class="text-center">
                        <p>FOR PURCHASING ORDER <b>#<?= $order->id; ?></b></p>
                        <p>Your order will not be confirmed until you complete the payment, please click the link below
                            and complete the payment.</p>
                        <p>Expires at: <span class="text-danger"><?= $charge['expires_at']; ?></span></p>
                        <p><a class="btn" style="background: #ff6129; color: #fff" href="<?= $charge['hosted_url']; ?>" target="_blank">Click here to paid</a></p>
                        <a class="button button-secondary button-nina" href="<?= $viewUrl; ?>"> View Order </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
