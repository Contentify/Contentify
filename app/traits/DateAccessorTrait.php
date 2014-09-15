<?php

/*
 * Use this trait to "extend" Eloquent models
 * so they use Contentify\Carbon. Take a look at the
 * Eloquent model class if you are interested in
 * the original methods.
 */
trait DateAccessorTrait {

    public function freshTimestamp()
    {
        return new Carbon;
    }

    public function fromDateTime($value)
    {
        $format = $this->getDateFormat(); // This is an Eloquent method

        if ($value instanceof DateTime)
        {
            // Do nothing
        }
        elseif (is_numeric($value))
        {
            $value = Carbon::createFromTimestamp($value);
        }
        elseif (preg_match('/^(\d{4})-(\d{2})-(\d{2})$/', $value))
        {
            $value = Carbon::createFromFormat('Y-m-d', $value)->startOfDay();
        }
        elseif ( ! $value instanceof DateTime)
        {
            $value = Carbon::createFromFormat($format, $value);
        }

        return $value->format($format);
    }

    protected function asDateTime($value)
    {
        if (is_numeric($value))
        {
            return Carbon::createFromTimestamp($value);
        }
        elseif (preg_match('/^(\d{4})-(\d{2})-(\d{2})$/', $value))
        {
            return Carbon::createFromFormat('Y-m-d', $value)->startOfDay();
        }
        elseif ( ! $value instanceof DateTime)
        {
            $format = $this->getDateFormat();

            return Carbon::createFromFormat($format, $value);
        }

        return Carbon::instance($value);
    }

}