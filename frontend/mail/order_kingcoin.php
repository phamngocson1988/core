<?php
use yii\helpers\Url;
?>
<p>Dear Sir/ Madam,</p>
<p>We are glad to inform that your deposit money has been creditted into your account on Kinggems.us. Kindly check the balance our website.</p>
<ul>
<li>The remainning balance: <?=$wallet->balance;?> Kcoins</li>
<li>Top up amount: <?=$wallet->coin;?> Kcoins</li>
<li>The last balance: <?=$wallet->balance - $wallet->coin;?> Kcoins</li>
</ul>
<p>Please Click the button below to confirm your transaction</p>

<a href="<?=Url::to(['user/wallet'], true);?>">CONFIRM</a>

<p>Hope you enjoy the game!</p>

<p>Thanks and Regards,</p>
<hr/>
<p>Date: example <?=$wallet->created_at;?></p>
<p>Dear Sir/Madam,
<p>
Thank you for choosing KINGGEMS as your transaction. We are delighted to confirm that the deposit money has been transfered into your account on Kinggems.us successfully. Could you please help us check the information on the website for the previous balance, Topup amount and the remaining 
balance to ensure that everything will be accurate in Kcoins.
</p>
<p>
We are looking forward to supporting all of your needs and welcoming your questions to deal with every financial issues. Moreover, we would be grateful 
if you could send us the confirmation by clicking the Button below:
</p>
<a href="<?=Url::to(['user/wallet'], true);?>">CONFIRM</a>
<p>Yours faithfully,</p>