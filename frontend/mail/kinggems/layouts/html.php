<?php
use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this \yii\web\View view component instance */
/* @var $message \yii\mail\MessageInterface the message being composed */
/* @var $content string main view render result */
?>
<?php $this->beginPage() ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=<?= Yii::$app->charset ?>" />
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body>
    <?php $this->beginBody() ?>
    <div style="background:#fafafa;background-position:center top;background-repeat:repeat-x;font-family:Roboto,Helvetica Neue,Helvetica,Arial,sans-serif;font-size:0px;margin:0;padding:0 20px">
        <table align="center" style="width:100%" border="0" cellspacing="0" cellpadding="0">
          <tbody>
            <tr>
              <td align="center" style="padding-top:30px;padding-left:0px;padding-right:0px">
                <table align="center" style="max-width:475px;width:100%" border="0" cellspacing="0" cellpadding="0">
                  <tbody>
                    <tr>
                      <td style="padding-top:10px">
                        <table align="center" border="0" cellpadding="0" cellspacing="0" style="margin:0 auto;width:100%">
                          <tbody>
                            <tr>
                              <td style="padding-bottom:30px;font-size:0px;line-height:0px">&nbsp;</td>
                            </tr>
                            <tr>
                              <td>
                                <img alt="" border="0" src="https://thienanit.com/wp-content/uploads/2020/05/banner_kinggems.jpg" style="height:auto;width:100%" height="auto" width="100%" tabindex="0">
                              </td>
                            </tr>
    			             <?= $content ?>
                            <tr bgcolor="#392a2e">
                                <td style="padding-top:20px;padding-bottom:20px;padding-right:30px;padding-left:30px;border-bottom:1px solid #dddddd;border-right:1px solid #dddddd;border-left:1px solid #dddddd">
                        <table align="center" border="0" cellpadding="0" cellspacing="0" style="margin:0 auto;width:100%">
                          <tbody>
                            <tr>
                              <td align="center" dir="ltr" style="padding-bottom: 20px; font-family: Roboto,Helvetica Neue,Helvetica,Arial,sans-serif; font-weight: normal; font-size: 12px; line-height: 20px; color: #9fb1c1">
                                <center>
                                  Folow us on socials
                                </center>
                              </td>
                            </tr>
                          </tbody>
                        </table>
                        <table dir="ltr" align="center" border="0" cellpadding="0" cellspacing="0" class="m_-8883938515098928479m_-6467991901041391641social" width="40%">
                          <tbody>
                            <tr>
                              <td align="center" bgcolor="#392a2e" valign="top">
                                <a href="https://fb.com/ThienAnIT" style="font-family:Roboto,Helvetica Neue,Helvetica,Arial,sans-serif;font-weight:normal;text-decoration:none" target="_blank"><img alt="" border="0" src="https://thienanit.com/wp-content/uploads/2020/05/facebook.png" style="display:block;height:auto;padding: 0px 5px;" width="40"> </a>
                              </td>
                              <td width="15" bgcolor="#392a2e">&nbsp;</td>
                              <td align="center" bgcolor="#392a2e" valign="top">
                                <a href="mailto:sale@thienanit.com" style="font-family:Roboto,Helvetica Neue,Helvetica,Arial,sans-serif;font-weight:normal;text-decoration:none" target="_blank"><img alt="" border="0" src="https://thienanit.com/wp-content/uploads/2020/05/whatsapp.png" style="display:block;height:auto;padding: 0px 5px;" width="40"></a>
                              </td>
                              <td width="15" bgcolor="#392a2e">&nbsp;</td>
                              <td align="center" bgcolor="#392a2e" valign="top">
                                <a href="https://thienanit.com/lien-he" style="font-family:Roboto,Helvetica Neue,Helvetica,Arial,sans-serif;font-weight:normal;text-decoration:none" target="_blank"><img alt="" border="0" src="https://thienanit.com/wp-content/uploads/2020/05/telegram.png" style="display:block;height:auto;padding: 0px 5px;" width="40"></a>
                              </td>
                              <td width="15" bgcolor="#392a2e">&nbsp;</td>
                              <td align="center" bgcolor="#392a2e" valign="top">
                                <a href="https://thienanit.com" style="font-family:Roboto,Helvetica Neue,Helvetica,Arial,sans-serif;font-weight:normal;text-decoration:none" target="_blank"><img alt="" border="0" src="https://thienanit.com/wp-content/uploads/2020/05/wechat.png" style="display:block;height:auto;padding: 0px 5px;" width="40"></a>
                              </td>
                            </tr>
                          </tbody>
                        </table>
                      </td>
                    </tr>
                  </tbody>
                </table>
                <table align="center" border="0" cellpadding="0" cellspacing="0" style="margin:0 auto;width:100%">
                  <tbody>
                    <tr>
                      <td bgcolor="#fafafa" align="center" dir="ltr" style="padding-top: 30px; padding-bottom: 60px; font-family: Roboto,Helvetica Neue,Helvetica,Arial,sans-serif; font-weight: normal; font-size: 12px; line-height: 20px; color: #9fb1c1">
                        <center>
                          Fast Top-up | Cost savings | Multiple Choises <br/> 
                          Come to us to enjoy your game with acceptable cost and best quality service
                        </center>
                      </td>
                    </tr>
                  </tbody>
                </table>
              </td>
            </tr>
          </tbody>
        </table>
      </td>
    </tr>
  </tbody>
</table>
    <?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
