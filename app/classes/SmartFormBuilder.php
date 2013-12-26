<?php

class SmartFormBuilder extends Illuminate\Support\Facades\Form {

    /**
     * Create HTML code for error displayment
     * @param  MessageBag $errors The errors to display
     * @return string
     */
    public static function errors($errors)
    {
        if (is_subclass_of($errors, 'Illuminate\Support\MessageBag')) {
            return HTML::ul($errors->all(), ['class' => 'form-errors' ]);
        }
    }

    /**
     * Create HTML code for form action buttons (e. g. submit)
     * @param  array  $buttons Array of Buttons
     * @return string
     */
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

    /**
     * Create HTML code for a text input element.
     * @param  string $name  The name of the input element
     * @param  string $title The title of the input element
     * @return string
     */
    public static function smartText($name = 'email', $title = 'Email')
    {
        $partial = '<div class="form-group">'.self::label($name, $title).' '.self::text($name).'</div>';
        return $partial;
    }

    /**
     * Create HTML code for a email input element.
     * @param  string $name  The name of the input element
     * @param  string $title The title of the input element
     * @return string
     */
    public static function smartEmail($name = 'email', $title = 'Email')
    {
        $partial = '<div class="form-group">'.self::label($name, $title).' '.self::email($name).'</div>';
        return $partial;
    }

    /**
     * Create HTML code for a password input element.
     * @param  string $name  The name of the input element
     * @param  string $title The title of the input element
     * @return string
     */
    public static function smartPassword($name = 'password', $title = 'Password')
    {
        $partial = '<div class="form-group">'.self::label($name, $title).' '.self::password($name).'</div>';
        return $partial;
    }

    /**
     * Create HTML code for a textarea input element.
     * @param  string $name  The name of the input element
     * @param  string $title The title of the input element
     * @return string
     */
    public static function smartTextarea($name = 'email', $title = 'Email')
    {
        $partial = '<div class="form-group">'.self::label($name, $title).' '.self::textarea($name).'</div>';
        return $partial;
    }

    /**
     * Create HTML code for a image upload input element.
     * @param  string $name  The name of the input element
     * @param  string $title The title of the input element
     * @return string
     */
    public static function smartImageFile($name = 'image', $title = 'Image')
    {
        $partial = '<div class="form-group">'.self::label($name, $title).' '.self::file($name).'</div>';
        return $partial;
    }
}