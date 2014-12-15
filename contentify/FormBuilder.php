<?php namespace Contentify;
 
use Illuminate\Html\FormBuilder as OriginalFormBuilder;
use App, Crypt, URL, HTML, DB, Exception, MsgException;
use \Carbon as AliasedCarbon; // If we just use Carbon we would use Contentify\Carbon and ignoring the alias!

class FormBuilder extends OriginalFormBuilder {

    /**
     * Create HTML code for error displayment
     * 
     * @param  MessageBag $errors The errors to display
     * @return string
     */
    public function errors($errors)
    {
        if (is_a($errors, 'Illuminate\Support\MessageBag') or is_a($errors, 'Illuminate\Support\ViewErrorBag')) {
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
    public function actions($buttons = array('submit', 'apply', 'reset'), $showImages = true)
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
                    $partial .= self::button($value, $options);
                    break; 
                case 'apply':
                    $options['type'] = 'submit';
                    $options['name'] = '_form_apply';
                    if ($title == 'Submit') $title = trans('app.apply');
                    $value = $title;
                    if ($showImages) $value = HTML::image(
                        asset('icons/disk.png'), $value, ['width' => 16, 'height' => 16]
                    ).' '.$value;
                    $partial .= self::button($value, $options);
                    break; 
                case 'reset':
                    $options['type'] = $type;
                    if ($title == 'Submit') $title = trans('app.reset');
                    $value = $title;
                    if ($showImages) $value = HTML::image(
                        asset('icons/undo.png'), $value, ['width' => 16, 'height' => 16]
                    ).' '.$value;
                    $partial .= self::button($value, $options);
                    break; 
            }
            
        }
        return $partial.'</div>';
    }

    /**
     * Create HTML code for a number input element.
     * 
     * @param  string $name       The name of the input element
     * @param  string $value      The default value
     * @param  array  $options    Array with attributes
     * @return string
     */
    public function numeric($name, $value = null, $options = array())
    {
        if (isset($options['class'])) {
            $options['class'] = ' ';
        } else {
            $options['class'] = '';  
        }
        $options['class'] .= 'numeric-input';
        
        $partial = self::input('text', $name, $value, $options);
        return $partial;
    }

    /**
     * Create HTML code for a select element. It will take its values from a database table.
     * This is meant for models that do not extend the BaseModel class.
     * 
     * @param  string   $name     The name of the attribute, e. g. "user_id"
     * @param  string   $title    The title of the select element
     * @param  mixed    $default  Null or an ID
     * @param  bool     $nullable If true the result can be empty and a "none selected" option is added
     * @return string
     */
    public function selectForeign($name, $default = null, $nullable = false)
    {
        $pos = strrpos($name, '_');
        if ($pos === false) {
            throw new Exception("Invalid foreign key: {$name}", 1);
        }
        $modelName = str_plural(substr($name, 0, $pos));
        $attribute = substr($name, $pos + 1);

        $models = DB::table(str_plural($modelName))->whereDeletedAt(null)->get();

        if (! $nullable and sizeof($models) == 0) {
            throw new MsgException(trans('app.list_empty', [$modelName]));
        }

        $options = [];
        if ($nullable) $options[''] = '-';
        foreach ($models as $model) {
            if (isset($model->title)) {
                $modelTitle = 'title';
            } elseif (isset($model->name)) {
                $modelTitle = 'name';
            } else {
                $modelTitle = $model->getKeyName();
            }

            $options[$model->$attribute] = $model->$modelTitle;
        }

        $value = self::getDefaultValue($name, $default);

        return self::select($name, $options, $value);
    }

    /**
     * Create HTML code for the opening part of a custom form group.
     *
     * @param  string $title The name of the corresponding element (not the label itself!)
     * @param  string $title The title of the field
     * @return string
     */
    public function smartGroupOpen($name = null, $title = null)
    {
        $partial = '<div class="form-group">';

        if ($title) {
            $partial .= self::label($name, $title, ['class' => 'col-sm-2 control-label']);
        }

        return $partial.'<div class="col-sm-10">';
    }

    /**
     * Create HTML code for the closing part of a custom form group.
     * 
     * @param  string $title The title of the field
     * @return string
     */
    public function smartGroupClose()
    {
        return '</div></div>';
    }

    /**
     * Create HTML code for a checkbox element.
     * 
     * @param  string   $name       The name of the checkbox element
     * @param  string   $title      The title of the checkbox element
     * @param  bool     $default    The default value
     * @return string
     */
    public function smartCheckbox($name, $title, $default = null)
    {
        $value = self::getDefaultValue($name, $default);

        // Bugfix for Laravel checkbox bug ( http://nielson.io/2014/02/handling-checkbox-input-in-laravel/ ):
        $checkbox = self::hidden($name, false).self::checkbox($name, true, $default);
        $partial = self::smartGroupOpen($name, $title)
            .'<div class="checkbox">'.$checkbox.'</div>'
            .self::smartGroupClose();
        return $partial;
    }

    /**
     * Create HTML code for a text input element.
     * 
     * @param  string $name       The name of the input element
     * @param  string $title      The title of the input element
     * @param  string $default    The default value
     * @return string
     */
    public function smartText($name, $title, $default = null)
    {
        $value = self::getDefaultValue($name, $default);
        $partial = self::smartGroupOpen($name, $title)
            .self::text($name, $value)
            .self::smartGroupClose();
        return $partial;
    }

    /**
     * Create HTML code for a email input element.
     * 
     * @param  string $name       The name of the input element
     * @param  string $title      The title of the input element
     * @param  string $default    The default value
     * @return string
     */
    public function smartEmail($name = 'email', $title = null, $default = null)
    {
        if (! $title) $title = trans('app.email');

        $value = self::getDefaultValue($name, $default);
        $partial = self::smartGroupOpen($name, $title)
            .self::email($name, $value)
            .self::smartGroupClose();
        return $partial;
    }

    /**
     * Create HTML code for a URL input element.
     * @param  string $name       The name of the input element
     * @param  string $title      The title of the input element
     * @param  string $default    The default value
     * @return string
     */
    public function smartUrl($name = 'url', $title = null, $default = null)
    {
        if (! $title) $title = trans('app.url');

        $value = self::getDefaultValue($name, $default);
        $partial = self::smartGroupOpen($name, $title)
            .self::url($name, $value)
            .self::smartGroupClose();
        return $partial;
    }

    /**
     * Create HTML code for a password input element.
     * @param  string $name  The name of the input element
     * @param  string $title The title of the input element
     * @return string
     */
    public function smartPassword($name = 'password', $title = null)
    {
        if (! $title) $title = trans('app.password');

        $partial = self::smartGroupOpen($name, $title)
            .self::password($name)
            .self::smartGroupClose();
        return $partial;
    }

    /**
     * Create HTML code for a textarea input element.
     * 
     * @param  string $name       The name of the input element
     * @param  string $title      The title of the input element
     * @param  string $default    The default value
     * @return string
     */
    public function smartTextarea($name = 'text', $title = null, $editor = true, $default = null)
    {
        $value = self::getDefaultValue($name, $default);

        if (! $title) $title = trans('app.text');

        if ($editor) {
            $label      = self::label($name, $title, ['class' => 'full-line']);
            $textarea   = self::textarea($name, $value, ['class' => 'editor']);

            $code = "<script>var editorLocale = '".App::getLocale()."';
            CKEDITOR.replace('".$name."', {
                customConfig: 'custom_config.js'
            });</script>";

            $partial    = '<div class="form-editor">'
                .$label
                .$textarea
                .$code
                .'</div>';
        } else {
            $partial    = self::smartGroupOpen($name, $title)
                .self::textarea($name, $value)
                .self::smartGroupClose();
        }
        
        return $partial;
    }

    /**
     * Create HTML code for a numeric input element.
     * 
     * @param  string $name       The name of the input element
     * @param  string $title      The title of the input element
     * @param  string $default    The default value
     * @return string
     */
    public function smartNumeric($name, $title, $default = null)
    {
        $value = self::getDefaultValue($name, $default);
        $partial = self::smartGroupOpen($name, $title)
            .self::numeric($name, $value)
            .self::smartGroupClose();
        return $partial;
    }

    /**
     * Create HTML code for a select element.
     * 
     * @param  string       $name       The name of the select element
     * @param  string       $title      The title of the select element
     * @param  array        $options    Array of options (pairs of titles and values)
     * @param  mixed        $default    Values of preselected options
     * @param  array        $attributes Additional HTML attributes
     * @return string
     */
    public function smartSelect($name, $title, $options, $default = null, $attributes = array())
    {
        $value = self::getDefaultValue($name, $default);

        $partial = self::smartGroupOpen($name, $title)
            .self::select($name, $options, $value, $attributes)
            .self::smartGroupClose();
        return $partial;
    }

    /**
     * Create HTML code for a select element. It will take its values from a database table.
     * This is meant for models that do not extend the BaseModel class.
     * 
     * @param  string   $name     The name of the attribute, e. g. "user_id"
     * @param  string   $title    The title of the select element
     * @param  mixed    $default  Null or an ID
     * @param  bool     $nullable If true the result can be empty and a "none selected" option is added
     * @return string
     */
    public function smartSelectForeign($name, $title, $default = null, $nullable = false)
    {
        $partial = self::smartGroupOpen($name, $title)
            .self::selectForeign($name, $default, $nullable)
            .self::smartGroupClose();
        return $partial;
    }

    /**
     * Create HTML code for a select element. It will take its values from a database table.
     * It's smart and able to understand relationships of a model.
     * 
     * @param  string   $name               The name of the relation as defined in $model::relations
     * @param  string   $title              The caption of the select element
     * @param  string   $sourceModelClass   Full name of the source model class
     * @param  mixed    $default            Null, an ID or an an array of IDs (if multiple selected items are possible)
     * @param  bool     $nullable           If true the select element can be empty
     * @param  bool     $nullOption         If true an extra element that has a null value is added
     * @return string
     */
    public function smartSelectRelation($relationName, $title, $sourceModelClass, $default = null, 
        $nullable = false, $nullOption = false)
    {
        $relations = $sourceModelClass::relations();
        
        if (isset($relations[$relationName])) {
            $relation = $relations[$relationName];
        } else {
            throw new Exception(
                "Error: Relation '{$relationName}' does not exist for model of type '{$sourceModelClass}'."
            );
        }

        $modelClass = $relation[1]; // Fully classified name of the foreign model
        $modelName  = class_basename($modelClass);
        $key        = (new $modelClass)->getKeyName(); // Primary key of the model
        if (isset($relation['foreignKey'])) $key = $relation['foreignKey'];

        $models = $modelClass::all();

        if (! $nullable and sizeof($models) == 0) {
            throw new MsgException(trans('app.list_empty', [ucfirst($relationName)]));
        }

        /*
         * Find an attribute that will be displayed as title
         */
        $options = [];
        if ($nullOption) {
            $options[''] = '-';
        }
        foreach ($models as $model) {
            if (isset($relation['title'])) {
                $modelTitle = $relation['title'];
            } else {
                if (isset($model->title)) {
                    $modelTitle = 'title';
                } elseif (isset($model->name)) {
                    $modelTitle = 'name';
                } else {
                    $modelTitle = $model->getKeyName();
                }
            }                    

            $options[$model->$key] = $model->$modelTitle;
        }

        $elementAttributes = [];

        /*
         * Handle the different types of relations
         */
        switch ($relation[0]) {
            case 'belongsTo':    
                $default = self::getDefaultValue(snake_case($relationName).'_'.$key, $default);

                break;
            case 'belongsToMany':
                $sourceModel    = new $sourceModelClass;
                $sourceKey      = class_basename(strtolower($sourceModelClass)).'_'.$sourceModel->getKeyName();
                $sourceKeyValue = self::getValueAttribute($sourceModel->getKeyName());

                /*
                 * If a model is bound to the form, $sourceKeyValue is not null and
                 * we can look up in the pivot table for related models.
                 * If not, the default value(s) will be passed to the select element.
                 */
                if ($sourceKeyValue) {
                    // We assume that soft deletion is not available to relations (= entries of pivot tables)
                    $models = DB::table($relation['table'])->where($sourceKey, '=', $sourceKeyValue)->get();

                    foreach ($models as $model) {
                        $default[] = $model->{strtolower($modelName).'_'.$key};
                    }
                }

                $elementAttributes  = ['multiple' => 'multiple'];
                $relationName       .= '[]';

                break;
            default:
                throw new Exception("Error: Unkown relation type '{$relation[0]}' for model of type '{$modelName}'.");
        }
        
        $name       = '_relation_'.$relationName;
        $partial    = self::smartGroupOpen($name, $title)
            .self::hidden($name, false) // Dummy so even if no option is selected some data is sent
            .self::select($name, $options, $default, $elementAttributes)
            .self::smartGroupClose();
        return $partial;
    }

    /**
     * Create HTML code for a file upload input element.
     * 
     * @param  string $name  The name of the input element
     * @param  string $title The title of the input element
     * @return string
     */
    public function smartFile($name = 'file', $title = null)
    {
        if (! $title) $title = trans('app.file');

        $partial = self::smartGroupOpen($name, $title)
            .self::file($name)
            .' '.trans('app.max_size', [ini_get('upload_max_filesize')])
            .self::smartGroupClose();
        return $partial;
    }

    /**
     * Create HTML code for an image upload input element.
     * 
     * @param  string $name  The name of the input element
     * @param  string $title The title of the input element
     * @return string
     */
    public function smartImageFile($name = 'image')
    {
        if (! $title) $title = trans('app.image');

        return self::smartFile($name, $title);
    }

    /**
     * Create HTML code for an icon upload input element.
     * 
     * @param  string $name  The name of the input element
     * @param  string $title The title of the input element
     * @return string
     */
    public function smartIconFile($name = 'icon', $title = null)
    {
        if (! $title) $title = trans('app.icon');

        return self::smartImageFile($name, $title);
    }

    /**
     * Create HTML code for a email input element.
     *
     * @param  string $name  The name of the input element
     * @param  string $title The title of the input element
     * @return string
     */
    public function smartCaptcha($name = 'captcha', $title = 'Captcha')
    {
        $image      = HTML::image(URL::route('captcha'), 'Captcha');
        $partial    = self::smartGroupOpen($name, $title)
            .$image.' '
            .self::text($name)
            .self::smartGroupClose();
        return $partial;
    }

    /**
     * Create HTML code for a date & time input element.
     * 
     * @param  string $name       The name of the input element
     * @param  string $title      The title of the input element
     * @param  string $default    The default value
     * @return string
     */
    public function smartDateTime($name = 'datetime', $title = null, $default = null)
    {
        if (! $title) $title = trans('app.date_time');

        $value = self::getDefaultValue($name, $default);

        /*
         * If $value is not null and is an object (Carbon instance),
         * localize date and time.
         * If $value is null initialize it with the current date & time.
         */
        if ($value and ! is_string($value)) {
            $value = $value->dateTime();
        } elseif ($value === null) {
            $value = (new AliasedCarbon())->dateTime();
        }

        $partial = '<div class="form-group date-time-picker input-append date">'
            .'<label for="'.$name.'" class="col-sm-2 control-label">'.$title.'</label>'
            .'<div class="col-sm-10">'
            .self::text($name, $value, ['data-format' => trans('app.date_format_alt').' hh:mm:ss'])
            .'<span class="add-on"><img src="'.asset('icons/date.png').'" alt="Pick"/></span>'
            .'</div></div>';
        return $partial;
    }

    /**
     * Create HTML code for a tag element.
     * 
     * @param  string $name       The name of the tag element
     * @param  string $title      The title of the tag element
     * @param  string $default    The default value
     * @return string
     */
    public function smartTags($name, $title, $default = null)
    {
        $value = self::getDefaultValue($name, $default);

        $partial = self::smartGroupOpen($name, $title)
            .self::text($name, $value, ['data-role' => 'tagsinput', 'placeholder' => trans('app.add_tags')])
            .self::smartGroupClose();
        return $partial;
    }

    /**
     * Adds a hidden field with a timestamp (of the current time)
     *
     * @param string    $name       Name of the field
     * @param bool      $encrypt    Encrypt the value?
     * @return string
     */
    public function timestamp($name = '_created_at', $encrypt = true)
    {
        $time = time();

        if ($encrypt) $time = Crypt::encrypt($time);

        return self::hidden($name, $time);
    }

    /**
     * Laravel prioritises model values lower than the value passed to form elements.
     * This method is an alternative to getValueAttribute() that prioritises model values higher.
     * 
     * @param string $name
     * @param mixed  $default
     * @return mixed
     */
    public function getDefaultValue($name, $default)
    {
        $value = self::getValueAttribute($name);

        $value = $value ? $value : $default;

        return $value;
    }

}