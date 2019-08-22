<?php
use yii\helpers\Url;
?>
<p style="margin: 4px 0 10px;">Dear Sir/ Madam,</p>
<p style="margin: 4px 0 10px;">We are glad to inform that your deposit money has been creditted into your account on Kinggems.us. Kindly check the balance our website.</p>
<ul>
<li>The remainning balance: <?=$wallet->balance;?> Kcoins</li>
<li>Top up amount: <?=$wallet->coin;?> Kcoins</li>
<li>The last balance: <?=$wallet->balance - $wallet->coin;?> Kcoins</li>
</ul>
<p style="margin: 4px 0 10px;">Please Click the button below to confirm your transaction</p>

<div style="margin:auto">
    <a href="<?=Url::to(['user/wallet'], true);?>" style="display:inline-block;text-decoration:none;background-color:#ffc107!important;margin-right:30px;text-align:center;border-radius:3px;color:#fff;padding:5px 10px;font-size:12px;font-weight:bold;margin-left:35%;margin-top:5px" target="_blank">CONFIRM</a>
</div>

<p style="margin: 4px 0 10px;">Hope you enjoy the game!</p>

<p style="margin: 4px 0 10px;">Thanks and Regards,</p>
<hr/>
<p style="margin: 4px 0 10px;">Date: example <?=$wallet->created_at;?></p>
<p style="margin: 4px 0 10px;">Dear Sir/Madam,
<p style="margin: 4px 0 10px;">
Thank you for choosing KINGGEMS as your transaction. We are delighted to confirm that the deposit money has been transfered into your account on Kinggems.us successfully. Could you please help us check the information on the website for the previous balance, Topup amount and the remaining 
balance to ensure that everything will be accurate in Kcoins.
</p>
<p style="margin: 4px 0 10px;">
We are looking forward to supporting all of your needs and welcoming your questions to deal with every financial issues. Moreover, we would be grateful 
if you could send us the confirmation by clicking the Button below:
</p>
<div style="margin:auto">
    <a href="<?=Url::to(['user/wallet'], true);?>" style="display:inline-block;text-decoration:none;background-color:#ffc107!important;margin-right:30px;text-align:center;border-radius:3px;color:#fff;padding:5px 10px;font-size:12px;font-weight:bold;margin-left:35%;margin-top:5px" target="_blank">CONFIRM</a>
</div>
<p style="margin: 4px 0 10px;">Yours faithfully,</p>