<tr>
  <td align="initial" dir="ltr" style="padding-bottom: 22px; font-weight: normal; font-size: 13px; line-height: 18px; color: #404041; text-align: left;">
    <P>Dear <?= $user->name;?></P>
    <P>To activate your Kinggems Account, please verify your email address.</P>
    <p><a href='<?=$activeUrl;?>' target='_blank' style='text-decoration: none; color: #ffc107'>Confirm your email.</a></p>
    <p>Or, copy and paste the following URL into your browser:</p>
    <p><a href='<?=$activeUrl;?>' target='_blank'><?=$activeUrl;?></a></p>
    <p>Username: <?=$user->username;?></p>
    <p>Password: <?=$password;?></p>
  </td>
</tr>