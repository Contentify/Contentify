<?php namespace Contentify;

use Session, Str, Request, Config;

class Captcha {

    /**
     * Creates a new captcha image and displays it
     * 
     * @return void
     */
    public function make() 
    {
        $captchaCode = strtolower(Str::random(4));
        Session::put('captchaCode', $captchaCode);

        $img = imagecreatefromjpeg(public_path().'/theme/captcha.jpg'); // Create image from file
        
        $color      = imagecolorallocate($img, rand(0, 50), rand(0, 50), rand(0, 50));
        $font       = public_path().'/theme/xfiles.ttf';
        $fheight    = 12; // Font height
        $angle      = rand(-3, 3);
        $x          = rand(3, 17);
        $y          = 16; // y = 0 is located at the BOTTOM of the picture!
        
        imagettftext($img, $fheight, $angle, $x, $y, $color, $font, $captchaCode); // Add text to image
        
        imagejpeg($img); // Display image
        
        imagedestroy($img); // Delete image from memory
    }
    
    /**
     * Checks if the captcha code is valid
     * 
     * @param   string  $value The potential captcha code
     * @return  bool
     */
    public function check($code) 
    {
        // Note: We do not need (want?) a strict string comparison here.
        return ($code == Session::get('captchaCode'));
    }
    
    /**
     * Checks if the captcha code is valid, 
     * using Google ReCAPTCHA.
     * 
     * @param   string  $value The potential captcha code
     * @return  bool
     */
    public function checkReCaptcha($code)
    {
        $data = [
            'secret'    => Config::get('app.recaptcha_secret'),
            'response'  => $code,
            'remoteip'  => Request::getClientIp(),
        ];

        $url = 'https://www.google.com/recaptcha/api/siteverify';

        $curl = curl_init();
        
        curl_setopt($curl, CURLOPT_HEADER, false);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, true);
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data);         

        $result = curl_exec($curl);

        $resultArray = json_decode($result, true);

        return (isset($resultArray['success']) and $resultArray['success']);
    }

}   