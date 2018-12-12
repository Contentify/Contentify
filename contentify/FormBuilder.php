<?php

namespace Contentify;
 
use App;
use Carbon as AliasedCarbon; // If we just use Carbon we would use Contentify\Carbon and ignoring the alias!
use Collective\Html\FormBuilder as OriginalFormBuilder;
use Crypt;
use DB;
use Exception;
use HTML;
use InvalidArgumentException;
use MsgException;
use URL;

class FormBuilder extends OriginalFormBuilder
{

    /**
     * Form groups: Number of grid columns of the label column
     * 
     * @var string
     */
    protected $labelGridCols = 3;

    /**
     * Form groups: Number of grid columns of the controls column
     * 
     * @var string
     */
    protected $controlGridCols = 9;

    /**
     * Create HTML code for displaying errors
     * 
     * @param  \Illuminate\Support\MessageBag $errors The errors to display
     * @return string|null
     */
    public function errors($errors)
    {

        if (is_a($errors, 'Illuminate\Support\MessageBag') or is_a($errors, 'Illuminate\Support\ViewErrorBag')) {
            $list = '';
            foreach ($errors->getMessages() as $fieldName => $fieldMessages) {
                foreach ($fieldMessages as $fieldMessage) {
                    $list .= '<li data-field="'.e($fieldName).'">'.$fieldMessage.'</li>';
                }
            }

            return  $list ? '<ul class="form-errors">'.$list.'</ul>' : '';
        }

        return null;
    }

    /**
     * Open up a new HTML form. 
     * Sets "form-horizontal" as the default class for forms.
     *
     * @param  array  $options
     * @return string
     */
    public function open(array $options = array())
    {
        if (! isset($options['class'])) {
            $options['class'] = 'form-horizontal';
        }

        return parent::open($options);
    }

    /**
     * Create HTML code for form action buttons (e. g. submit).
     * Available string values for the $buttons array are: "submit", "apply", "reset"
     * If you want to add options, use these values as a key and its options as the value.
     *
     * Examples:
     *
     * Form::actions(); // Create all default buttons
     * Form::actions(['submit', 'apply']); // Create only two buttons
     * Form::actions(['submit' => trans('app.send')]); // Create a submit button with a particular title
     * Form::actions(['submit' =>['title' => 'Create', 'data-id' => 123]); // Create a button with an extra attribute
     * 
     * @param  array $buttons    Array of button configurations
     * @param  bool  $showImages Show icons on the buttons?
     * @return string
     */
    public function actions(array $buttons = array('submit', 'apply', 'cancel'), $showImages = true)
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

                    if ($title == 'Submit') {
                        $title = trans('app.save');
                    }

                    $value = $title;
                    if ($showImages) {
                        $value = HTML::fontIcon('save').' '.$value;
                    }

                    $partial .= self::button($value, $options);

                    break; 
                case 'apply':
                    $options['type'] = 'submit';
                    $options['name'] = '_form_apply';

                    if ($title == 'Submit' or $title == 'Apply') {
                        $title = trans('app.apply');
                    }

                    $value = $title;
                    if ($showImages) {
                        $value = HTML::fontIcon('save').' '.$value;
                    }

                    $partial .= self::button($value, $options);

                    break; 
                case 'reset':
                    $options['type'] = $type;

                    if ($title == 'Submit' or $title == 'Reset') {
                        $title = trans('app.reset');
                    }

                    $value = $title;
                    if ($showImages) {
                        $value = HTML::fontIcon('undo').' '.$value;
                    }

                    $partial .= self::button($value, $options);
                
                    break;
                case 'cancel':
                    if ($title == 'Cancel') {
                        $title = trans('app.cancel');
                    }

                    if (isset($options['url'])) {
                        $url = $options['url'];
                    } else {
                        // Remove the last part of the URL.
                        // That - of course - will not work always.
                        $url = URL::current();
                        $baseUrl = url('admin/');
                        $pos = strpos($url, '/', strlen($baseUrl) + 1);
                        if ($pos !== false) {
                            $url = substr(URL::current(), 0, $pos);
                        }
                    }

                    $partial .= HTML::button($title, $url, $showImages ? 'times' : '', $options);

                    break;
            }
            
        }

        return $partial.'</div>';
    }

    /**
     * Create a button element.
     *
     * @see HTML::button()
     *
     * @param  string|null $value   The value (= label) of the button
     * @param  array       $options Array with attributes
     * @return string
     */
    public function button($value = null, $options = array())
    {
        if (! array_key_exists('class', $options))
        {
            $options['class'] = 'btn btn-default';
        }
        if (! array_key_exists('value', $options)) {
            $options['value'] = '1'; // Set a value, or the value will be an empty string which evaluates to null
        }

        return parent::button($value, $options);
    }

    /**
     * Create HTML code for a number input element.
     * 
     * @param  string      $name    The name of the input element
     * @param  string|null $value   The default value
     * @param  array       $options Array with attributes
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
        
        #$partial = self::input('text', $name, $value, $options);
        $partial = self::number($name, $value, $options);

        return $partial;
    }

    /**
     * Create HTML code for a URL input element.
     * 
     * @param  string      $name    The name of the input element
     * @param  string|null $value   The default value
     * @param  array       $options Array with attributes
     * @return string
     */
    public function url($name, $value = null, $options = array())
    {
        if (isset($options['class'])) {
            $options['class'] = ' ';
        } else {
            $options['class'] = '';
        }
        $options['class'] .= 'url';

        if (! isset($options['placeholder'])) {
            $options['placeholder'] = 'https://www.example.com';
        }
        
        $partial = self::input('url', $name, $value, $options);
        return $partial;
    }

    /**
     * Create HTML code for a select element. It will take its values from a database table.
     * This is meant for models that do not extend the BaseModel class.
     *
     * @param  string     $name     The name of the attribute, e. g. "user_id"
     * @param  mixed|null $default  Null or an ID
     * @param  bool       $nullable If true the result can be empty and a "none selected" option is added
     * @return string
     * @throws Exception
     */
    public function selectForeign($name, $default = null, $nullable = false)
    {
        $pos = strrpos($name, '_');
        if ($pos === false) {
            throw new InvalidArgumentException("Invalid foreign key: {$name}", 1);
        }
        $modelName = str_plural(substr($name, 0, $pos));
        $attribute = substr($name, $pos + 1);

        /*
         * We have a problem here. We do not know the exact model. Therefore there is no way to
         * check if it uses soft deletion or not. The dirty way is to assume it does and to blindly 
         * try to query the models. If it does not have the deleted_at attribute we catch the DB 
         * exception and give it another try - without the WHERE clause.
         */
        try {
            $models = DB::table(str_plural($modelName))->whereDeletedAt(null)->get();
        } catch (Exception $e) {
            $models = DB::table(str_plural($modelName))->get();
        }

        if (! $nullable and sizeof($models) == 0) {
            throw new MsgException(trans('app.list_empty', [$modelName]));
        }

        $options = [];
        if ($nullable) {
            $options[''] = '-';
        }
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
     * @param  string|null $name  The name of the corresponding element (not the label itself!)
     * @param  string|null $title The title of the field
     * @param  string|null $class Additional class(es)
     * @return string
     */
    public function smartGroupOpen($name = null, $title = null, $class = null)
    {
        $partial = '<div class="form-group '.$class.'">';

        if ($title) {
            $partial .= self::label($name, $title, ['class' => 'col-sm-'.$this->labelGridCols.' control-label']);
        }

        return $partial.'<div class="col-sm-'.$this->controlGridCols.'">';
    }

    /**
     * Create HTML code for the closing part of a custom form group.
     * 
     * @return string
     */
    public function smartGroupClose()
    {
        return '</div></div>';
    }

    /**
     * Create HTML code for a checkbox element.
     * 
     * @param  string    $name    The name of the checkbox element
     * @param  string    $title   The title of the checkbox element
     * @param  bool|null $default The default value
     * @return string
     */
    public function smartCheckbox($name, $title, $default = null)
    {
        $value = self::getDefaultValue($name, $default);

        // We add a hidden field as a bugfix for a Laravel checkbox bug
        // ( http://nielson.io/2014/02/handling-checkbox-input-in-laravel/ )
        // We set the value to 0 instead of false, because false will be
        // transformed to an empty string and MySQL cannot handle that
        $checkbox = self::hidden($name, 0).self::checkbox($name, 1, $value);
        $partial = self::smartGroupOpen($name, $title)
            .'<div class="checkbox">'.$checkbox.'</div>'
            .self::smartGroupClose();
        return $partial;
    }

    /**
     * Create HTML code for a text input element.
     * 
     * @param  string      $name    The name of the input element
     * @param  string      $title   The title of the input element
     * @param  string|null $default The default value
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
     * @param  string      $name    The name of the input element
     * @param  string|null $title   The title of the input element
     * @param  string|null $default The default value
     * @return string
     */
    public function smartEmail($name = 'email', $title = null, $default = null)
    {
        if (! $title) {
            $title = trans('app.email');
        }

        $value = self::getDefaultValue($name, $default);
        $partial = self::smartGroupOpen($name, $title)
            .self::email($name, $value)
            .self::smartGroupClose();
        return $partial;
    }

    /**
     * Create HTML code for a URL input element.
     * 
     * @param  string      $name    The name of the input element
     * @param  string|null $title   The title of the input element
     * @param  string|null $default The default value
     * @return string
     */
    public function smartUrl($name = 'url', $title = null, $default = null)
    {
        if (! $title) {
            $title = trans('app.url');
        }

        $value = self::getDefaultValue($name, $default);
        $partial = self::smartGroupOpen($name, $title)
            .self::url($name, $value)
            .self::smartGroupClose();
        return $partial;
    }

    /**
     * Create HTML code for a password input element.
     * 
     * @param  string      $name  The name of the input element
     * @param  string|null $title The title of the input element
     * @return string
     */
    public function smartPassword($name = 'password', $title = null)
    {
        if (! $title) {
            $title = trans('app.password');
        }

        $partial = self::smartGroupOpen($name, $title)
            .self::password($name)
            .self::smartGroupClose();
        return $partial;
    }

    /**
     * Create HTML code for a textarea input element.
     * 
     * @param  string      $name    The name of the input element
     * @param  string|null $title   The title of the input element
     * @param  bool        $editor  Add WYSIWYG editor? The editor will create HTML code.
     * @param  string|null $default The default value
     * @return string
     */
    public function smartTextarea($name = 'text', $title = null, $editor = false, $default = null)
    {
        $value = self::getDefaultValue($name, $default);

        if (! $title) {
            $title = trans('app.text');
        }

        if ($editor) {
            $label    = self::label($name, $title, ['class' => 'full-line']);
            $textarea = self::textarea($name, $value, ['class' => 'editor']);

            $code = "<script>var editorLocale = '".App::getLocale()."';
            var config = window.innerWidth > 768 ? 'custom_config.js' : 'custom_config_mobile.js';
            CKEDITOR.replace('".$name."', {
                customConfig: config
            });</script>";

            $partial  = '<div class="form-editor">'
                .$label
                .$textarea
                .$code
                .'</div>';
        } else {
            $partial  = self::smartGroupOpen($name, $title)
                .self::textarea($name, $value)
                .self::smartGroupClose();
        }
        
        return $partial;
    }

    /**
     * Create HTML code for a numeric input element.
     * 
     * @param  string      $name       The name of the input element
     * @param  string      $title      The title of the input element
     * @param  string|null $default    The default value
     * @param  array       $attributes Additional HTML attributes
     * @return string
     */
    public function smartNumeric($name, $title, $default = null, $attributes = array())
    {
        $value = self::getDefaultValue($name, $default);
        $partial = self::smartGroupOpen($name, $title)
            .self::numeric($name, $value, $attributes)
            .self::smartGroupClose();
        return $partial;
    }

    /**
     * Create HTML code for a select element.
     * 
     * @param  string     $name       The name of the select element
     * @param  string     $title      The title of the select element
     * @param  array      $options    Array of options (pairs of titles and values)
     * @param  mixed|null $default    Values of preselected options
     * @param  array      $attributes Additional HTML attributes
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
     * @param  string     $name     The name of the attribute, e. g. "user_id"
     * @param  string     $title    The title of the select element
     * @param  mixed|null $default  Null or an ID
     * @param  bool       $nullable If true the result can be empty and a "none selected" option is added
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
     * @param  string     $relationName     The name of the relation as defined in $model::relations
     * @param  string     $title            The caption of the select element
     * @param  string     $sourceModelClass Full name of the source model class
     * @param  mixed|null $default          Null, an ID or an an array of IDs (if multiple selected items are possible)
     * @param  bool       $nullable         If true the select element can be empty
     * @param  bool       $nullOption       If true an extra element that has a null value is added
     * @return string
     * @throws Exception
     */
    public function smartSelectRelation(
        $relationName,
        $title,
        $sourceModelClass,
        $default = null,
        $nullable = false,
        $nullOption = false
    )
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
        if (isset($relation['foreignKey'])) {
            $key = $relation['foreignKey'];
        }

        /** @var \Illuminate\Database\Eloquent\Model[] $models */
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
            // A relation might have a value with the key 'title' that
            // contains the name of the model attribute that represents
            // the model title
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
                /** @var \Illuminate\Database\Eloquent\Model $sourceModel */
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
                throw new Exception("Error: Unknown relation type '{$relation[0]}' for model of type '{$modelName}'.");
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
     * @param  string      $name  The name of the input element
     * @param  string|null $title The title of the input element
     * @return string
     */
    public function smartFile($name = 'file', $title = null)
    {
        if (! $title) {
            $title = trans('app.file');
        }

        $partial = self::smartGroupOpen($name, $title)
            .'<div class="input-group">'
            .self::file($name, ['class' => 'form-control', 'data-info' => trans('app.save_to_del')])
            .'<span class="input-group-addon delete">'.HTML::fontIcon('trash').'</span>'
            .'</div>'
            .'<p class="help-block">'.trans('app.max_size', [ini_get('upload_max_filesize')]).'</p>'
            .self::smartGroupClose();
        return $partial;
    }

    /**
     * Create HTML code for an image upload input element.
     * 
     * @param  string      $name  The name of the input element
     * @param  string|null $title The title of the input element
     * @return string
     */
    public function smartImageFile($name = 'image', $title = null)
    {
        if (! $title) {
            $title = trans('app.image');
        }

        $prev = '';
        $value = $this->getDefaultValue($name, null);
        if ($value and method_exists($this->model, 'uploadPath')) {
            $path = $this->model->uploadPath().$value;
            $prev = '<div class="image-upload-prev">'
                .'<a href="'.$path.'" target="_blank">'.HTML::image($path, $title).'</a>'
                .'</div>';
        }

        $partial = self::smartGroupOpen($name, $title)
            .$prev
            .'<div class="input-group">'
            .self::file($name, ['class' => 'form-control', 'data-info' => trans('app.save_to_del')])
            .'<span class="input-group-addon delete">'.HTML::fontIcon('trash').'</span>'
            .'</div>'
            .'<p class="help-block">'.trans('app.max_size', [ini_get('upload_max_filesize')]).'</p>'
            .self::smartGroupClose();
        return $partial;
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
        if (! $title) {
            $title = trans('app.icon');
        }

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
            .'<div class="captcha">'.$image.' '
            .self::text($name)
            .'</div>'
            .self::smartGroupClose();
        return $partial;
    }

    /**
     * Create HTML code for a date & time input element.
     * 
     * @param  string      $name     The name of the input element
     * @param  string|null $title    The title of the input element
     * @param  string|null $default  The default value
     * @param  bool        $onlyDate If true, do not display time
     * @return string
     */
    public function smartDateTime($name = 'datetime', $title = null, $default = null, $onlyDate = false)
    {
        if (! $title) {
            $title = trans('app.date_time');
        }

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

        $time = '';
        if (! $onlyDate) {
            $time = ' HH:mm:ss';
        }

        $partial = '<div class="form-group">'
            .'<label for="'.$name.'" class="col-sm-'.$this->labelGridCols.' control-label">'.$title.'</label>'
            .'<div class="col-sm-'.$this->controlGridCols.' ">'
            .'<div class="input-group date-time-picker">'
            .self::text($name, $value, ['class' => 'form-control', 'data-format' => trans('app.date_format_alt').$time])
            .'<span class="input-group-addon">'.HTML::fontIcon('calendar').'</span>'
            .'</div></div></div>';
        return $partial;
    }

    /**
     * Create HTML code for a date input element.
     * 
     * @param  string      $name    The name of the input element
     * @param  string|null $title   The title of the input element
     * @param  string|null $default The default value
     * @return string
     */
    public function smartDate($name = 'date', $title = null, $default = null)
    {
        return self::smartDateTime($name, $title, $default, true);
    }
    
    /**
     * Create HTML code for a tag element.
     * 
     * @param  string      $name    The name of the tag element
     * @param  string      $title   The title of the tag element
     * @param  string|null $default The default value
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
     * @param string $name    Name of the field
     * @param bool   $encrypt Encrypt the value?
     * @return string
     */
    public function timestamp($name = '_created_at', $encrypt = true)
    {
        $time = time();

        if ($encrypt) {
            $time = Crypt::encrypt($time);
        }

        return self::hidden($name, $time);
    }

    /**
     * Adds a Bootstrap help block.
     * 
     * @param  string $text The text inside the block.
     * @return string
     */
    public function helpBlock($text) 
    {
        $partial = '<div class="form-group">'
            .'<div class="col-sm-'.$this->labelGridCols.' "></div>'
            .'<div class="col-sm-'.$this->controlGridCols.' ">'
            .'<span class="help-block">'.$text.'</span>'
            .'</div></div>';

        return $partial;
    }

    /**
     * Laravel prioritises model values lower than the value passed to form elements.
     * This method prioritises model values higher an therefore is an alternative
     * to getValueAttribute().
     * 
     * @param string $name
     * @param mixed  $default
     * @return mixed
     */
    public function getDefaultValue($name, $default)
    {
        $value = self::getValueAttribute($name);

        $value = $value !== null ? $value : $default;

        return $value;
    }

    /**
     * Setter for $this->labelGridCols
     *
     * @param int $number
     * @return void
     */
    public function labelGridCols($number)
    {
        $this->labelGridCols = $number;
    }

    /**
     * Setter for $this->controlGridCols
     *
     * @param int $number
     * @return void
     */
    public function controlGridCols($number)
    {
        $this->controlGridCols = $number;
    }

}
