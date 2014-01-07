<?php

class Captcha {

	/**
	 * Creates a new captcha image and displays it
	 * @return void
	 */
	public static function make() 
	{
		$captchacode = strtolower(Str::random(4));
		Session::put('captchacode', $captchacode);

		$img = ImageCreateFromJPEG(public_path().'/theme/captcha.jpg'); // Create image from file
		
		$color 		= ImageColorAllocate($img, rand(0, 50), rand(0, 50), rand(0, 50));
		$font 		= public_path().'/theme/xfiles.ttf';
		$fheight 	= 12; // Font height
		$angle 		= rand(-3, 3);
		$x 			= rand(3, 17);
		$y 			= 16; // y = 0 is located at the BOTTOM of the picture!
		
		imagettftext($img, $fheight, $angle, $x, $y, $color, $font, $captchacode); // Add text to image
		
		imagejpeg($img); // Display image
		
		imagedestroy($img); // Delete image from memory
	}
	
	/**
	 * Checks if the captcha code is valid
	 * @return  bool
 	 */
	public static function check($value) 
	{
		if ($value == Session::get('captchacode')) {
			return true;
		} else {
			return false;
		}
	}
}	