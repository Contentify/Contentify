<?php

class SmartFormBuilder extends Illuminate\Support\Facades\Form {

    /**
     * Create HTML code for error displayment
     * @param  MessageBag $errors The errors to display
     * @return string
     */
    public static function errors($errors)
    {
        if (is_a($errors, 'Illuminate\Support\MessageBag')) {
            return HTML::ul($errors->all(), ['class' => 'form-errors' ]);
        }
    }

    /**
     * Create HTML code for form action buttons (e. g. submit)
     * @param  array  $buttons Array of Buttons
     * @return string
     */
    public static function actions($buttons = array('submit', 'apply', 'reset'))
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
                $title = ucfirst($type);
                $options = array();
            }

            switch (strtolower($type)) {
                case 'submit':
                    $options['type'] = $type;
                    $options['name'] = '_form_submit';
                    $value = HTML::image(asset('icons/disk.png'), 'Save', ['width' => 16, 'height' => 16]).' Save';
                    $partial .= Form::button($value, $options);
                    break; 
                case 'apply':
                    $options['type'] = $type;
                    $options['name'] = '_form_apply';
                    $value = HTML::image(asset('icons/disk.png'), 'Save &amp; Continue', ['width' => 16, 'height' => 16]).' '.$title;
                    $partial .= Form::button($value, $options);
                    break; 
                case 'reset':
                    $options['type'] = $type;
                    $value = HTML::image(asset('icons/disk2.png'), 'Reset', ['width' => 16, 'height' => 16]).' '.$title;
                    $partial .= Form::button($value, $options);
                    break; 
            }
            
        }
        return $partial.'</div>';
    }

    /**
     * Create HTML code for a checkbox element.
     * @param  string $name  The name of the checkbox element
     * @param  string $title The title of the checkbox element
     * @return string
     */
    public static function smartCheckbox($name = 'image', $title = 'Image')
    {
        $partial = '<div class="form-group">'.self::label($name, $title).' '.self::checkbox($name).'</div>';
        return $partial;
    }

    /**
     * Create HTML code for a text input element.
     * @param  string $name  The name of the input element
     * @param  string $title The title of the input element
     * @return string
     */
    public static function smartText($name, $title)
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
    public static function smartTextarea($name = 'text', $title = 'Text', $editor = true)
    {
        $partial = '<div class="form-group">'.self::label($name, $title, ['class' => 'full-line']).' '.self::textarea($name, NULL, ['class' => 'ckeditor']).'</div>';
        return $partial;
    }

    /**
     * Create HTML code for a select element.
     * @param  string $name  The name of the select element
     * @param  string $title The title of the select element
     * @return string
     */
    public static function smartSelectForeign($name, $title)
    {
        $model = str_replace(strtolower('_id'), '', $name);
        $entities = DB::table(str_plural($model))->get();

        $options = array();
        foreach ($entities as $entity) {
            if (isset($entity->title)) {
                $entityTitle = 'title';
            } else {
                $entityTitle = 'id';
            }

            $options[$entity->id] = $entity->$entityTitle;
        }
        
        $partial = '<div class="form-group">'.self::label($name, $title).' '.self::select($name, $options).'</div>';
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