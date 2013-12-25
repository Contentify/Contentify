<?php

class SmartFormBuilder extends Illuminate\Support\Facades\Form {

    public static function errors($errors)
    {
        if (is_subclass_of($errors, 'Illuminate\Support\MessageBag')) {
            return HTML::ul($errors->all(), ['class' => 'form-errors' ]);
        }
    }

    public static function actions($buttons = array('submit'))
    {
        $partial = '<div class="form-actions">';
        foreach ($buttons as $type => $options) {
            if (is_string($type)) {
                if (is_string($options)) {
                    $title = $options;
                    $options = array();
                } else {
                    $title = $options['title'];
                    unset($options['title']);
                }
            } else {
                $type = $options;
                $title = 'Save';
                $options = array();
            }

            switch (strtolower($type)) {
                case 'submit':
                    $partial .= Form::submit($title, $options).' ';
                    break; 
            }
            
        }
        return $partial.'</div>';
    }

    public static function smartText($name = 'email', $title = 'Email')
    {
        $partial = '<div class="form-group">'.self::label($name, $title).' '.self::text($name).'</div>';
        return $partial;
    }

    public static function smartEmail($name = 'email', $title = 'Email')
    {
        $partial = '<div class="form-group">'.self::label($name, $title).' '.self::email($name).'</div>';
        return $partial;
    }

    public static function smartPassword($name = 'password', $title = 'Password')
    {
        $partial = '<div class="form-group">'.self::label($name, $title).' '.self::password($name).'</div>';
        return $partial;
    }

    public static function smartTextarea($name = 'email', $title = 'Email')
    {
        $partial = '<div class="form-group">'.self::label($name, $title).' '.self::textarea($name).'</div>';
        return $partial;
    }

    public static function smartImageFile($name = 'image', $title = 'Image')
    {
        $partial = '<div class="form-group">'.self::label($name, $title).' '.self::file($name).'</div>';
        return $partial;
    }
}