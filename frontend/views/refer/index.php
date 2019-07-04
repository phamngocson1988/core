<?php
use yii\helpers\Url;
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
$link = Url::to(['site/signup', 'refer' => $user->affiliate_code], true);
?>
<span id="link"><?=$link;?></span>

<a href="https://www.facebook.com/sharer/sharer.php?u=<?=$link;?>&t=Kinggems Title"
   onclick="javascript:window.open(this.href, '', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=300,width=600');return false;"
   target="_blank" title="Share on Facebook">Share on Facebook
</a>
|
<a href="https://twitter.com/share?url=<?=$link;?>&via=TWITTER_HANDLE&text=Kinggems Title"
   onclick="javascript:window.open(this.href, '', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=300,width=600');return false;"
   target="_blank" title="Share on Twitter">Share on Twitter
</a>

<?php $form = ActiveForm::begin(); ?>
<div id='email_list'>
    <?php foreach(range(0, 19) as $i) {
        $name = Html::textInput("refers[$i][name]");
        $email = Html::textInput("refers[$i][email]");
        echo Html::tag('div', $name . $email, ['class' => 'form-group']);
    }?>
</div>
<?=Html::submitButton('Submit');?>
<?php ActiveForm::end();?>