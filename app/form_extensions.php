<?php

/*
|--------------------------------------------------------------------------
| Form Extensions
|--------------------------------------------------------------------------
|
| This is the right place to setup global form extensions (macros).
|
*/

Form::macro('errors', 
    /**
     * Create HTML code for error displayment
     * 
     * @param  MessageBag $errors The errors to display
     * @return string
     */
    function ($errors)
    {
        if (is_a($errors, 'Illuminate\Support\MessageBag')) {
            return HTML::ul($errors->all(), ['class' => 'form-errors' ]);
        }
    }
);

Form::macro('actions', 
    /**
     * Create HTML code for form action buttons (e. g. submit)
     * 
     * @param  array  $buttons Array of Buttons
     * @param  bool   $showImages Show icons on the buttons?
     * @return string
     */
    function ($buttons = array('submit', 'apply', 'reset'), $showImages = true)
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
);

Form::macro('numeric', 
    /**
     * Create HTML code for a number input element.
     * 
     * @param  string $name       The name of the input element
     * @param  string $value      The default value
     * @param  array  $options    Array with attributes
     * @return string
     */
    function ($name, $value = null, $options = array())
    {
        if (isset($options['class'])) {
            $options['class'] = ' ';
        } else {
            $options['class'] = '';  
        }
        $options['class'] .= 'numeric-input';
        
        $partial = Form::input('text', $name, $value, $options).'</div>';
        return $partial;
    }
);

Form::macro('smartFieldOpen', 
    /**
     * Create HTML code for the opening part of a custom form field.
     * 
     * @param  string $title The title of the field
     * @return string
     */
    function ($title)
    {
        $partial = '<div class="form-group">'.Form::label('', $title).' ';
        return $partial;
    }
);

Form::macro('smartFieldClose', 
    /**
     * Create HTML code for the closing part of a custom form field.
     * 
     * @param  string $title The title of the field
     * @return string
     */
    function ()
    {
        return '</div>';
    }
);

Form::macro('smartCheckbox', 
    /**
     * Create HTML code for a checkbox element.
     * 
     * @param  string       $name       The name of the checkbox element
     * @param  string       $title      The title of the checkbox element
     * @param  bool|null    $default    The default value
     * @return string
     */
    function ($name, $title, $default = null)
    {
        $value = Form::getDefaultValue($name, $default);

        // Bugfix for Laravel checkobx bug ( http://nielson.io/2014/02/handling-checkbox-input-in-laravel/ ):
        $checkbox = Form::hidden($name, false).Form::checkbox($name, true, $default);
        $partial = '<div class="form-group">'.Form::label($name, $title).' '.$checkbox.'</div>';
        return $partial;
    }
);

Form::macro('smartText', 
    /**
     * Create HTML code for a text input element.
     * 
     * @param  string       $name       The name of the input element
     * @param  string       $title      The title of the input element
     * @param  string|null  $default    The default value
     * @return string
     */
    function ($name, $title, $default = null)
    {
        $value = Form::getDefaultValue($name, $default);
        $partial = '<div class="form-group">'.Form::label($name, $title).' '.Form::text($name, $value).'</div>';
        return $partial;
    }
);

Form::macro('smartEmail', 
    /**
     * Create HTML code for a email input element.
     * 
     * @param  string       $name       The name of the input element
     * @param  string       $title      The title of the input element
     * @param  string|null  $default    The default value
     * @return string
     */
    function ($name = 'email', $title = 'Email', $default = null)
    {
        $value = Form::getDefaultValue($name, $default);
        $partial = '<div class="form-group">'.Form::label($name, $title).' '.Form::email($name, $value).'</div>';
        return $partial;
    }
);

Form::macro('smartPassword', 
    /**
     * Create HTML code for a password input element.
     * @param  string $name  The name of the input element
     * @param  string $title The title of the input element
     * @return string
     */
    function ($name = 'password', $title = 'Password')
    {
        $partial = '<div class="form-group">'.Form::label($name, $title).' '.Form::password($name).'</div>';
        return $partial;
    }
);

Form::macro('smartTextarea', 
    /**
     * Create HTML code for a textarea input element.
     * 
     * @param  string       $name       The name of the input element
     * @param  string       $title      The title of the input element
     * @param  string|null  $default    The default value
     * @return string
     */
    function ($name = 'text', $title = 'Text', $editor = true, $default = null)
    {
        $value = Form::getDefaultValue($name, $default);

        if ($editor) {
            $label      = Form::label($name, $title, ['class' => 'full-line']);
            $textarea   = Form::textarea($name, $value, ['class' => 'ckeditor']);
        } else {
            $label      = Form::label($name, $title);
            $textarea   = Form::textarea($name, $value);
        }

        $partial    = '<div class="form-group">'.$label.' '.$textarea.'</div>';
        return $partial;
    }
);

Form::macro('smartNumeric', 
    /**
     * Create HTML code for a numeric input element.
     * 
     * @param  string       $name       The name of the input element
     * @param  string       $title      The title of the input element
     * @param  string|null  $default    The default value
     * @return string
     */
    function ($name, $title, $default = null)
    {
        $value = Form::getDefaultValue($name, $default);
        $partial = '<div class="form-group">'.Form::label($name, $title).' '.Form::numeric($name, $value).'</div>';
        return $partial;
    }
);

Form::macro('smartSelectForeign',
    /**
     * Create HTML code for a select element. It will take its values from a database table.
     * This is meant for models that do not support Ardent relationships.
     * 
     * @param  string   $name     The name of the value, e. g. "user_id"
     * @param  string   $title    The title of the select element
     * @param  mixed    $default  Null or an ID
     * @param  bool     $notEmpty If true the result cannot be empty (if it's empty throw an error)
     * @return string
     */
    function ($name, $title, $default = null, $notEmpty = true)
    {
        $pos = strrpos($name, '_');
        if ($pos === false) {
            throw new Exception("Invalid foreign key: {$name}", 1);
        }
        $model = str_plural(substr($name, 0, $pos));
        $attribute = substr($name, $pos + 1);

        $entities = DB::table(str_plural($model))->get();

        if ($notEmpty and sizeof($entities) == 0) {
            throw new Exception('Missing entities for foreign relationship.');
        }

        $options = array();
        foreach ($entities as $entity) {
            if (isset($entity->title)) {
                $entityTitle = 'title';
            } elseif (isset($entity->name)) {
                $entityTitle = 'name';
            } else {
                $entityTitle = 'id';
            }

            $options[$entity->$attribute] = $entity->$entityTitle;
        }

        $value = Form::getDefaultValue($name, $default);
        
        $partial = '<div class="form-group">'
            .Form::label($name, $title).' '
            .Form::select($name, $options, $value)
            .'</div>';
        return $partial;
    }
);

Form::macro('smartSelectRelation',
    /**
     * Create HTML code for a select element. It will take its values from a database table.
     * It's smart and able to understand Ardent relationships of a model.
     * 
     * @param  string   $name           The name of the relation as defined in $model::relations
     * @param  string   $title          The caption of the select element
     * @param  string   $sourceModel    Name of the source model
     * @param  mixed    $default        Null, an ID or an an array of IDs (if multiple selected items are possible)
     * @param  bool     $notEmpty       If true the select element cannot be empty (if it's empty throw an error)
     * @return string
     */
    function ($relationName, $title, $sourceModel, $default = null, $notEmpty = true)
    {
        $relations = $sourceModel::relations();
        
        if (isset($relations[$relationName])) {
            $relation = $relations[$relationName];
        } else {
            throw new Exception("Error: Relation '{$relationName}' does not exist for entity of type '{$sourceModel}'.");
        }

        $modelFull  = $relation[1]; // Fully classified name of the foreign model
        $model      = class_basename($modelFull);
        $key        = (new $modelFull)->getKeyName(); // Primary key of the model
        if (isset($relation['foreignKey'])) $key = $relation['foreignKey'];

        $entities = $modelFull::all();

        if ($notEmpty and sizeof($entities) == 0) {
            throw new Exception("Missing entities for relation '{$relationName}'.");
        }

        /*
         * Find an attribute that will be displayed as title
         */
        $options = array();
        foreach ($entities as $entity) {
            if (isset($relation['title'])) {
                $entityTitle = $relation['title'];
            } else {
                if (isset($entity->title)) {
                    $entityTitle = 'title';
                } elseif (isset($entity->name)) {
                    $entityTitle = 'name';
                } else {
                    $entityTitle = 'id';
                }
            }                    

            $options[$entity->$key] = $entity->$entityTitle;
        }

        $elementAttributes = [];

        /*
         * Handle the different types of relations
         */
        switch ($relation[0]) {
            case 'belongsTo':           
                $default = Form::getDefaultValue($relationName.'_'.$key, $default);

                break;
            case 'belongsToMany':
                $sourceEntity   = new $sourceModel;
                $sourceKey      = class_basename(strtolower($sourceModel)).'_'.$sourceEntity->getKeyName();
                $sourceKeyValue = Form::getValueAttribute($sourceEntity->getKeyName());

                /*
                 * If a model is bound to the form $sourceKeyValue is not null and
                 * we can look up in the pivot table for related entities.
                 * If not, the default value(s) will be passed to the select element.
                 */
                if ($sourceKeyValue) {
                    // We assume that soft deletion is not available to relations (= entries of pivot tables)
                    $entities = DB::table($relation['table'])->where($sourceKey, '=', $sourceKeyValue)->get();

                    foreach ($entities as $entity) {
                        $default[] = $entity->{strtolower($model).'_'.$key};
                    }
                }

                $elementAttributes  = ['multiple' => 'multiple'];
                $relationName       .= '[]';

                break;
            default:
                throw new Exception("Error: Unkown relation type '{$relation[0]}' for entity of type '{$model}'.");
        }
        
        $name       = '_relation_'.$relationName;
        $partial    = '<div class="form-group">'
            .Form::label($name, $title).' '
            .Form::hidden($name, false) // Dummy so even if no option is selected some data is sent
            .Form::select($name, $options, $default, $elementAttributes)
            .'</div>';
        return $partial;
    }
);

Form::macro('smartImageFile',
   /**
     * Create HTML code for an image upload input element.
     * 
     * @param  string $name  The name of the input element
     * @param  string $title The title of the input element
     * @return string
     */
    function ($name = 'image', $title = 'Image')
    {
        $partial = '<div class="form-group">'.Form::label($name, $title).' '.Form::file($name).'</div>';
        return $partial;
    }
);

Form::macro('smartIconFile',
    /**
     * Create HTML code for an icon upload input element.
     * 
     * @param  string $name  The name of the input element
     * @param  string $title The title of the input element
     * @return string
     */
    function ($name = 'icon', $title = 'Icon')
    {
        return Form::smartImageFile($name, $title);
    }
);

Form::macro('smartCaptcha',
    /**
     * Create HTML code for a email input element.
     *
     * @param  string $name  The name of the input element
     * @param  string $title The title of the input element
     * @return string
     */
    function ($name = 'captcha', $title = 'Captcha')
    {
        $label      = Form::label($name, $title);
        $image      = HTML::image(URL::route('captcha'), 'Captcha');
        $partial    = '<div class="form-group">'.$label.' '.$image.' '.Form::text($name).'</div>';
        return $partial;
    }
);

Form::macro('smartDateTime',
    /**
     * Create HTML code for a datetime input element.
     * 
     * @param  string       $name       The name of the input element
     * @param  string       $title      The title of the input element
     * @param  string|null  $default    The default value
     * @return string
     */
    function ($name = 'datetime', $title = 'Date & Time', $default = null)
    {
        $value = Form::getDefaultValue($name, $default);

        $partial = '<div class="form-group date-time-picker input-append date">'
            .Form::label($name, $title).' '
            .Form::text($name, $value, ['data-format' => trans('app.date_format').' hh:mm:ss'])
            .'<span class="add-on"><img src="'.asset('icons/date.png').'" alt="Pick"/></span>'
            .'</div>';
        return $partial;
    }
);

Form::macro('smartTagify', 
    /**
     * Create HTML code for a text input element.
     * 
     * @param  string       $name       The name of the input element
     * @param  string       $title      The title of the input element
     * @param  string|null  $default    The default value
     * @return string
     */
    function ($name, $title, $default = null)
    {
        $value = Form::getDefaultValue($name, $default);
        $partial = '<div class="form-group">'.Form::label($name, $title).' '.Form::text($name, $value, ['data-role' => 'tagsinput', 'placeholder' => 'Add tags']).'</div>';
        return $partial;
    }
);

Form::macro('getDefaultValue',
    /**
     * Laravel prioritises model values lower than higher the value passed to form elements.
     * This macro is an alternative to getValueAttribute that prioritises model values higher.
     * 
     * @param string $name
     * @param mixed  $default
     * @return mixed
     */
    function ($name, $default)
    {
        $value = Form::getValueAttribute($name);

        $value =  $value ? $value : $default;

        return $value;
    }
);