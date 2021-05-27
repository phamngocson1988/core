<?php
use yii\helpers\Url;
$setting = Yii::$app->settings;
?>
<tr>
  <td align="initial" dir="ltr" style="padding-bottom: 22px; font-weight: normal; font-size: 13px; line-height: 18px; color: #404041; text-align: left;">
    Dear <?=$user->getName();?>,<br/>
    <span style="color:#178186">Hoanggianapgame.com</span> xin thông báo:<br/>
    <p>Bạn vừa gửi yêu cầu thêm tài khoản ngân hàng đăng kí rút tiền từ tài khoản với nội dung:</p>
    + Tên chủ tài khoản: <?=$model->account_name;?><br/>
    + Ngân hàng: <?=$bank->short_name;?><br/>
    + Số tài khoản: <?=$model->account_number;?><br/>
    <p>Để xác minh bạn là người gửi yêu cầu, vui lòng sử dụng mã xác minh bên dưới để hoàn tất yêu cầu: <span style='color: #ffc107; font-size: 16px'><?=$model->auth_key;?></span></p>
    <p>Nếu bạn không phải là người gửi yêu cầu, vui lòng liên hệ và thông báo với bộ phận hỗ trợ và chăm sóc khách hàng của chúng tôi. </p>
  </td>
</tr>