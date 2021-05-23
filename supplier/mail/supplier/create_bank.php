<?php
use yii\helpers\Url;
$setting = Yii::$app->settings;
?>
<p style="margin: 4px 0 10px;">Dear <?=$user->getName();?>, </p>
<p style="margin: 4px 0 10px;">Hoanggianapgame.com xin thông báo:</p>
<p style="margin: 4px 0 10px;">Bạn vừa gửi yêu cầu thêm tài khoản ngân hàng đăng kí rút tiền từ tài khoản với nội dung:</p>
<ul>
    <li>Tên chủ tài khoản: <?=$model->account_name;?></li>
    <li>Ngân hàng: <?=$bank->short_name;?></li>
    <li>Số tài khoản: <?=$model->account_number;?></li>
</ul>
<p style="margin: 4px 0 10px;">Để xác minh bạn là người gửi yêu cầu, vui lòng sử dụng mã xác minh bên dưới để hoàn tất yêu cầu: <span style='color: #ffc107; font-size: 16px'><?=$model->auth_key;?></span></p>
<p style="margin: 4px 0 10px;">Nếu bạn không phải là người gửi yêu cầu, vui lòng liên hệ và thông báo với bộ phận hỗ trợ và chăm sóc khách hàng của chúng tôi. </p>