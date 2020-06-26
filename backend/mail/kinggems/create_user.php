<tr>
  <td align="initial" dir="ltr" style="padding-bottom: 22px; font-weight: normal; font-size: 13px; line-height: 18px; color: #404041; text-align: left;">
    <P>Xin chào <?= $mail->name;?>, bạn được mời làm thành viên quản trị của website kinggems.us. Vui lòng sử dụng thông tin bên dưới để đăng nhập vào hệ thống</P>
    <p><strong>Website:</strong> <a href="https://admin.kinggems.us" target="_blank">Link đăng nhập trang quản lý admin</a></p>
	<p><strong>Tài khoản:</strong> <?= $mail->username ?></p>
	<p><strong>Mật khẩu:</strong> <?= $mail->password ?></p>
	<p><strong>Vai trò:</strong> <?= $mail->role ?><br></p>
  </td>
</tr>
