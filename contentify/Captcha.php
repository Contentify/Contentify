<?php

namespace Contentify;

use Request;
use Session;
use Str;

/**
 * This class creates a CAPTCHA ( https://en.wikipedia.org/wiki/CAPTCHA ) image and also
 * can check if the user entered it correctly. It also supports ReCAPTCHA.
 */
class Captcha
{

    /**
     * Name of the event that is fired when the simple CAPTCHA image has been generated
     */
    const EVENT_NAME_SIMPLE_CAPTCHA_GENERATED = 'contentify.captcha.simpleCaptchaGenerated';

    /**
     * Google ReCAPTCHA API URL
     */
    const API_URL = 'https://www.google.com/recaptcha/api/siteverify';

    /**
     * Creates a new captcha image and displays it
     *
     * @return void
     */
    public function make()
    {
        $captchaCode = strtolower(Str::random(4));
        Session::put('captchaCode', $captchaCode);

        $img = imagecreatefromjpeg(public_path().'/img/default/captcha.jpg'); // Create image from file

        $color      = imagecolorallocate($img, rand(0, 50), rand(0, 50), rand(0, 50));
        $font       = public_path().'/img/default/xfiles.ttf';
        $fontHeight = 12; // Font height
        $angle      = rand(-3, 3);
        $x          = rand(3, 17);
        $y          = 16; // y = 0 is located at the BOTTOM of the picture!

        imagettftext($img, $fontHeight, $angle, $x, $y, $color, $font, $captchaCode); // Add text to image

        imagejpeg($img); // Display image
        event(self::EVENT_NAME_SIMPLE_CAPTCHA_GENERATED, [$img]);

        imagedestroy($img); // Delete image from memory
    }

    /**
     * Checks if the captcha code is valid
     *
     * @param string $code The potential captcha code
     * @return bool
     */
    public function check(string $code) : bool
    {
        // Note: We do not need (want?) a strict string comparison here.
        return ($code == Session::get('captchaCode'));
    }

    /**
     * Checks if the captcha code is valid, using Google ReCAPTCHA.
     *
     * @param string $code The potential captcha code
     * @return bool
     */
    public function checkReCaptcha(string $code) : bool
    {
        $data = [
            'secret'    => Config::get('app.recaptcha_secret'),
            'response'  => $code,
            'remoteip'  => Request::getClientIp()
        ];

        $curl = curl_init();

        curl_setopt($curl, CURLOPT_URL, self::API_URL);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($data));
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

        $result = curl_exec($curl);

        $resultArray = json_decode($result, true);

        return (isset($resultArray['success']) and $resultArray['success']);
    }

}
