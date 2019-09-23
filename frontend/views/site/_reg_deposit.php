<?php
use yii\helpers\Url;
?>
<!-- <div class="reg-deposit">
    <div class="has-left-border has-shadow">
        <img src="/images/ico-deposit-large.png" alt="">
        <p class="large-txt">
        Deposit
        </p>
        <p class="small-txt">Fast, Safe and Secure!</p>
    </div>
</div> -->
<div class="reg-useful-tools">
    <h3>Useful Tools</h3>
    <div class="has-left-border gray has-shadow">
        <img src="/images/ico-how-to-deposit.png" alt="">
        <p class="small-txt">How to</p>
        <p class="large-txt"><a href="<?=Url::to(['site/question-detail', 'id' => 25, 'slug' => 'how-to-deposit']);?>">Deposit</a></p>
    </div>
    <div class="has-left-border gray has-shadow">
        <img src="/images/ico-how-to-transfer.png" alt="">
        <p class="small-txt">How to</p>
        <p class="large-txt"><a href="<?=Url::to(['site/term', 'slug' => 'member']);?>">Transfer</a></p>
    </div>
    <div class="has-left-border gray has-shadow">
        <img src="/images/ico-how-to-play.png" alt="">
        <p class="small-txt">How to</p>
        <p class="large-txt"><a href="<?=Url::to(['site/question-detail', 'id' => 17, 'slug' => 'how-to-place-an-order']);?>">Order</a></p>
    </div>
</div>