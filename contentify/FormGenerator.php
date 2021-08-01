<?php

namespace Contentify;

use DB;
use Lang;
use Str;
use View;

/**
 * This class automatically generates a form template (Blade syntax)
 * based upon an existing table of the database. This is useful when you
 * want to create CRUD for an entity. The result will not be perfect but
 * it can save you a lot of work.
 */
class FormGenerator
{
    /**
     * Name of the event that is fired when the fields have been built
     */
    const EVENT_NAME_FIELDS_BUILT = 'contentify.formGenerator.fieldsBuilt';

    /**
     * True if the form has to handle file uploads
     *
     * @var boolean
     */
    protected $fileHandling;

    /**
     * The name of the module
     *
     * @var string
     */
    protected $moduleName;

    /**
     * Generates a simple but CMS compliant form template (Blade syntax).
     * Do not blindly trust the result - most likely refactoring is necessary.
     *
     * @param  string      $tableName  The table (representing a model) to generate a form for
     * @param  string|null $moduleName The module name - leave it empty if it's the table name
     * @return string
     */
    public function generate(string $tableName, string $moduleName = null) : string
    {
        if ($moduleName == null) {
            $moduleName = $tableName;
        }

        $this->moduleName = $moduleName;

        $this->fileHandling = false;

        $columns = DB::select('SHOW COLUMNS FROM '.$tableName);

        $fields = [];
        foreach ($columns as $columnIndex => $column) {
            $field = $this->buildField($column);
            if ($field) {
                $fields[] = $field;
            }
        }

        event(self::EVENT_NAME_FIELDS_BUILT, [$fields]);

        if ($this->fileHandling) {
            $fileHandling = ", 'files' => true";
        } else {
            $fileHandling = '';
        }

        $formView = View::make('formgenerator.template', compact('fields', 'moduleName', 'fileHandling'));

        return $formView->render();
    }

    /**
     * Creates a single form field.
     * Returns null if the field is ignored.
     *
     * @param  \stdClass $column The database column object
     * @return string
     */
    protected function buildField(\stdClass $column)
    {
        $ignoredFields = [
            'id',
            'access_counter',
            'creator_id',
            'updater_id',
            'created_at',
            'updated_at',
            'deleted_at'
        ];

        $name       = strtolower($column->Field);
        $type       = strtolower($column->Type);
        $meta       = '';
        $size       = 0;
        $required   = (strtolower($column->Null) == 'no');
        $default    = $column->Default;

        $title = $this->transformName($name);

        if ($default !== null) { // We need an exact match here (0 is a legit value!)
            $defParam = ", '{$default}'";
        } else {
            $defParam = '';
        }

        if (str_contains($type, '(')) {
            $pos    = strpos($type, '(');
            $meta   = substr($type, $pos);
            $type   = substr($type, 0, $pos);
        }

        if (Str::startsWith($meta, '(')) {
            $size   = (int) substr($meta, 1);
            $pos    = strpos($meta, ')');
            $meta   = trim(substr($meta, $pos + 1));
        }

        $html = null;
        if (! in_array($column->Field, $ignoredFields)) {
            if ($name == 'image' || $name == 'icon') {
                $type = 'image';
            }
            if ($name == 'email') {
                $type = 'email';
            }
            if ($name == 'password') {
                $type = 'password';
            }
            if ($name == 'url') {
                $type = 'url';
            }
            if (ends_with($name, '_id')) {
                $type = 'foreign';
            }

            $attributes = [];
            if ($size > 0) {
                $attributes['maxlength'] = $size;
            }
            if ($required) {
                $attributes['required'] = 'required';
            }

            switch ($type) {
                case 'tinyint':
                    if ($default === '0') { // "Not checked" is the default value of checkboxes
                        $defParam = '';
                    }
                    $html = "{!! Form::smartCheckbox('{$name}', {$title}{$defParam}) !!}";
                    break;
                case 'int':
                    unset($attributes['maxlength']);
                    $html = "{!! Form::smartNumeric('{$name}', {$title}{$defParam}) !!}";
                    break;
                case 'varchar':
                    $html = "{!! Form::smartText('{$name}', {$title}{$defParam}) !!}";
                    break;
                case 'text':
                    $html = "{!! Form::smartTextarea('{$name}', {$title}{$defParam}) !!}";
                    break;
                case 'email':
                    $html = "{!! Form::smartEmail('{$name}', {$title}{$defParam}) !!}";
                    break;
                case 'password':
                    $html = "{!! Form::smartPassword('{$name}', {$title}) !!}";
                    break;
                case 'url':
                    $html = "{!! Form::smartUrl('{$name}', {$title}{$defParam}) !!}";
                    break;
                case 'timestamp':
                    $html = "{!! Form::smartDateTime('{$name}', {$title}) !!}";
                    break;
                case 'image':
                    unset($attributes['maxlength']);
                    $this->fileHandling = true;
                    $html = "{!! Form::smartImageFile('{$name}', {$title}) !!}";
                    break;
                case 'foreign':
                    $title = $this->transformName(substr($name, 0, -3));
                    $name = Str::camel(substr($name, 0, -3)); // Eloquent can't handle snake_cased relation names
                    $html = "{!! Form::smartSelectForeign('{$name}', {$title}) !!}";
                    break;
                default:
                    $html = "<!-- Unknown type: {$type} -->";
                    break;
            }

            $html .= "\n";
        }

        return $html;
    }

    /**
     * If the attribute name $name is the name of a key in a translation file,
     * return code that calls the trans() function.
     * If not, make him readable at least.
     *
     * @param  string $name The attribute name
     * @return string
     */
    public function transformName(string $name) : string
    {
        if (Lang::has($this->moduleName.'::'.$name)) {
            return "trans('".$this->moduleName."::$name')";
        } elseif (Lang::has('app.'.$name)) {
            return "trans('app.$name')";
        } else {
            return "'".$this->makeTitle($name)."'";
        }
    }

    /**
     * Convert a snake_case string to a title (single words, ucfirst)
     *
     * @param  string $snakeCase The snake cased string
     * @return string
     */
    protected function makeTitle(string $snakeCase) : string
    {
        $words = explode('_', $snakeCase);

        for ($i = 0; $i < sizeof($words); $i++) {
            $words[$i] = ucfirst($words[$i]);
        }

        return implode(' ', $words);
    }
}
