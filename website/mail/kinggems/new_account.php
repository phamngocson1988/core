<?php
use yii\helpers\Html;
?>
<tr>
  <td align="initial" dir="ltr" style="padding-bottom: 22px; font-weight: normal; font-size: 13px; line-height: 18px; color: #404041; text-align: left;">
    <p>Dear <?= Html::encode($user->name) ?>,</p>
    <p>Hệ thống ghi nhận có một tài khoản khách hàng mới vừa được đăng ký trên website kinggems.us</p>
    <p>Thông tin tài khoản:</p>
    + Email: <?=$account->email;?><br/>
    + Số điện thoại: <?=$account->phone;?><br/>
    <p>Bộ phận chăm sóc khách hàng vui lòng liên hệ và hỗ trợ khách hàng.</p>
    <p>Regards,</p>
  </td>
</tr>