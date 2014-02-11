<?php namespace Contentify;

use HTML, Illuminate\Support\Facades\Form, URL, DB;

class FormBuilder extends Form {

    /**
     * Create HTML code for error displayment
     * 
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
     * 
     * @param  array  $buttons Array of Buttons
     * @param  bool   $showImages Show icons on the buttons?
     * @return string
     */
    public static function actions($buttons = array('submit', 'apply', 'reset'), $showImages = true)
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
                    if ($title == 'Submit') $title = trans('app.save');
                    $value = $title;
                    if ($showImages) $value = HTML::image(
                        asset('icons/disk.png'), $value, ['width' => 16, 'height' => 16]
                    ).' '.$value;
                    $partial .= Form::button($value, $options);
                    break; 
                case 'apply':
                    $options['type'] = $type;
                    $options['name'] = '_form_apply';
                    if ($title == 'Submit') $title = trans('app.apply');
                    $value = $title;
                    if ($showImages) $value = HTML::image(
                        asset('icons/disk.png'), $value, ['width' => 16, 'height' => 16]
                    ).' '.$value;
                    $partial .= Form::button($value, $options);
                    break; 
                case 'reset':
                    $options['type'] = $type;
                    if ($title == 'Submit') $title = trans('app.reset');
                    $value = $title;
                    if ($showImages) $value = HTML::image(
                        asset('icons/undo.png'), $value, ['width' => 16, 'height' => 16]
                    ).' '.$value;
                    $partial .= Form::button($value, $options);
                    break; 
            }
            
        }
        return $partial.'</div>';
    }

    /**
     * Create HTML code for a checkbox element.
     * 
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
     * 
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
     * 
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
     * 
     * @param  string $name  The name of the input element
     * @param  string $title The title of the input element
     * @return string
     */
    public static function smartTextarea($name = 'text', $title = 'Text', $editor = true)
    {
        $label      = self::label($name, $title, ['class' => 'full-line']);
        $textarea   = self::textarea($name, null, ['class' => 'ckeditor']);
        $partial    = '<div class="form-group">'.$label.' '.$textarea.'</div>';
        return $partial;
    }

    /**
     * Create HTML code for a select element.
     * 
     * @param  string $name  The name of the select element
     * @param  string $title The title of the select element
     * @return string
     */
    public static function smartSelectForeign($name, $title, $notEmpty = true)
    {
        $model = str_replace(strtolower('_id'), '', $name);
        $entities = DB::table(str_plural($model))->get();

        if ($notEmpty and sizeof($entities) == 0) {
            throw new Exception('Missing entities for foreign relationship.');
        }

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
     * Create HTML code for an image upload input element.
     * 
     * @param  string $name  The name of the input element
     * @param  string $title The title of the input element
     * @return string
     */
    public static function smartImageFile($name = 'image', $title = 'Image')
    {
        $partial = '<div class="form-group">'.self::label($name, $title).' '.self::file($name).'</div>';
        return $partial;
    }

    /**
     * Create HTML code for an icon upload input element.
     * 
     * @param  string $name  The name of the input element
     * @param  string $title The title of the input element
     * @return string
     */
    public static function smartIconFile($name = 'icon', $title = 'Icon')
    {
        return self::smartImageFile($name, $title);
    }

    /**
     * Create HTML code for a email input element.
     * 
     * @return string
     */
    public static function smartCaptcha($name = 'captcha', $title = 'Captcha')
    {
        $label      = self::label($name, $title);
        $image      = HTML::image(URL::route('captcha'), 'Captcha');
        $partial    = '<div class="form-group">'.$label.' '.$image.' '.self::text($name).'</div>';
        return $partial;
    }
}