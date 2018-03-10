<?php

namespace Contentify;
 
use Carbon\Carbon as OriginalCarbon;

class Carbon extends OriginalCarbon {

    /**
     * @return string
     */
    public function date()
    {
        return $this->format(self::$toStringFormat);
    }

    /**
     * @return string
     */
    public function dateTime()
    {
        return $this->format(self::$toStringFormat).' '.$this->format('H:i:s');
    }

}