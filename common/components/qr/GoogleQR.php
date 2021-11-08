<?php
namespace common\components\qr;

class GoogleQR
{
    //URL OF GOOGLE CHART API
    private $apiUrl = 'https://chart.apis.google.com/chart';
    // DATA TO CREATE QR CODE
    private $data;

    public function URL($url = null) 
    {
        $this->data = preg_match("#^https?\:\/\/#", $url) ? $url : "http://{$url}";
    }

    //Function which is used to save the qrcode image file.
    public function QRCODE($size = 400, $filename = null) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->apiUrl);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, "chs={$size}x{$size}&cht=qr&chl=" . urlencode($this->data));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        $img = curl_exec($ch);
        curl_close($ch);
        if ($img) {
            if ($filename) {
                if (!preg_match("#\.png$#i", $filename)) {
                    $filename .= ".png";
                }
                return file_put_contents($filename, $img);
            } else {
                return base64_encode($img);
            }
        }
        return false;
    }
}