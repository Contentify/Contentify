<?php namespace Contentify;
 
use Carbon\Carbon as OriginalCarbon;

class Carbon extends OriginalCarbon {
    
    public function date()
    {
        return $this->format(self::$toStringFormat);
    }

    public function dateTime()
    {
        return $this->format(self::$toStringFormat).' '.$this->format('H:m:s');
    }

}