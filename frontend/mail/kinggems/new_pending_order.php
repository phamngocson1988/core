<tr>
  <td align="initial" dir="ltr" style="padding-bottom: 22px; font-weight: normal; font-size: 13px; line-height: 18px; color: #404041; text-align: left;">
    <p>Dear <?=$order->customer_name;?>,</p>
    We would like to inform that Order #<?=$order->id;?>: Payment has been confirmed.
  </td>
</tr>
<tr>
  <td align="initial" dir="ltr" style="padding-bottom: 22px; font-weight: normal; font-size: 13px; line-height: 18px; color: #404041; text-align: left;">
    <hr>
    <p>Order #<?=$order->id;?> will be processd with following details:</p>
    + Game: <?=$order->game_title;?><br/>
    + Quantity / loaded amount: <?=number_format($order->quantity, 1);?><br/>
    + <?=$order->username;?><br/>
    + <?=$order->password;?><br/>
    + <?=$order->character_name;?><br/>
    <hr>
    <p>We’re happy to help you with any further questions or concerns. Please contact our customer services via :</p>
  </td>
</tr>
<tr>
  <td align="center" style="padding-top:0px;padding-bottom:0px">
  </td>
</tr>
<tr>
  <td align="center" style="padding-top:0px;padding-bottom:0px">
    <table border="0" cellpadding="0" cellspacing="0" width="60%">
      <tbody>
        <tr>
          <td align="center" dir="ltr">
            <center><a href="https://www.facebook.com/Kinggems.us" style="background:#E95D2B;border-radius:3px;color:#ffffff;display:block;font-family:Roboto,Helvetica Neue,Helvetica,Arial,sans-serif;font-weight:normal;letter-spacing:1px;padding:14px 8px;text-decoration:none;font-size:16px!important;" target="_blank">
              CONTACT
              </a>
            </center>
          </td>
        </tr>
      </tbody>
    </table>
  </td>
</tr>