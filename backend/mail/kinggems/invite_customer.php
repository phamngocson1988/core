<tr>
  <td align="initial" dir="ltr" style="padding-bottom: 22px; font-weight: normal; font-size: 13px; line-height: 18px; color: #404041; text-align: left;">
    <P>Xin chào <?= $mail->name;?>, tài khoản khách hàng của bạn trên hệ thống kinggems.us đã được tạo bởi admin</P>
    <p><strong>Website:</strong> <a href="https://kinggems.us/dang-nhap.html" target="_blank">Link đăng nhập</a></p>
	<p><strong>Tài khoản:</strong> <?= $mail->username ?></p>
	<p><strong>Mật khẩu:</strong> <?= $mail->password ?></p>
  </td>
</tr>
