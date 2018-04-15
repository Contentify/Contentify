<?php

namespace Contentify;
 
use Carbon\Carbon as OriginalCarbon;

class Carbon extends OriginalCarbon
{

    /**
     * Returns the date as a string in a format depending on the client
     *
     * @return string
     */
    public function date()
    {
        return $this->format(self::$toStringFormat);
    }

    /**
     * Returns the date in a format depending on the client and the time, both as a string
     *
     * @return string
     */
    public function dateTime()
    {
        return $this->format(self::$toStringFormat).' '.$this->format('H:i:s');
    }

}