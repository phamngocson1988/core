<tr>
  <td align="initial" dir="ltr" style="padding-bottom: 22px; font-weight: normal; font-size: 13px; line-height: 18px; color: #404041; text-align: left;">
    <P>Dear <?= $user->name ?>,</P>
    <p>Kindly be informed that one of your buyers has just sent payment to us, with details below: </p>
    + Sender's name: <?=$transaction->remark;?><br/>
    + Amount: <?=$transaction->total_price;?><br/>
    + Payment method: <?=sprintf("%s (%s)", $transaction->payment_method, $transaction->payment_type)?><br/>
    + Tracking No.: <?=$transaction->getId();?><br/>
    <p>So we will check and credit balance into your wallet accordingly. </p>
    <p>Kcoin Amoun:  <?=$transaction->total_coin;?></p>
    <p>You may check details on "Transaction history" with the said tracking No. </p>
    <p>Thanks for your cooperation! </p>
    <p>Kinggems Payment Team!</p>
  </td>
</tr>
<tr>
  <td align="center" style="padding-top:0px;padding-bottom:0px">
    <table border="0" cellpadding="0" cellspacing="0" width="40%">
      <tbody>
        <tr>
          <td align="center" dir="ltr">
            <center><a href="https://www.facebook.com/Kinggems.us" style="background:#E95D2B;border-radius:3px;color:#ffffff;display:block;font-family:Roboto,Helvetica Neue,Helvetica,Arial,sans-serif;font-weight:normal;letter-spacing:1px;padding:12px 8px;text-decoration:none;font-size:12px!important;" target="_blank">
              CONTACT
              </a>
            </center>
          </td>
        </tr>
      </tbody>
    </table>
  </td>
</tr>