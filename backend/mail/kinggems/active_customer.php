<tr>
  <td align="initial" dir="ltr" style="padding-bottom: 22px; font-weight: normal; font-size: 13px; line-height: 18px; color: #404041; text-align: left;">
    <P>Hello <?= $user->name;?></P>
    <P>Congrats! Your account has been created on Kinggems.us with the follow details:</P>
    <p>Username: <?=$user->username;?></p>
    <p>Password: <?=$password;?> (Please change the password later)</p>
    <P>Kindly active your account by accessing this link: </P>
    <p><a href='<?=$activeUrl;?>' target='_blank' style='text-decoration: none; color: #ffc107'>Confirm your email.</a></p>
    <p>Thanks for your cooperation & Hope you enjoy the journey with us!</p>
  </td>
</tr>