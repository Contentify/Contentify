<?php namespace Contentify;

use DB, View;

class FormGenerator {

    /**
     * True if form has to handle file uploads
     * @var boolean
     */
    protected $fileHandling;

    /**
     * Generates a simple but CMS compliant form template (Blade syntax).
     * Do not blindly trust the result - most likely refactoring is necessary.
     * 
     * @param  string $tableName  The table (representing a model) to generate a form for
     * @param  string $moduleName The module name - leave it empty if it's the table name
     * @return string
     */
    public function generate($tableName, $moduleName = null)
    {
        if ($moduleName == null) $moduleName = $tableName;

        $this->fileHandling = false;

        $columns = DB::select('SHOW COLUMNS FROM '.$tableName);

        $fields = array();
        foreach ($columns as $columnIndex => $column) {
            $field = $this->buildField($column);
            if ($field) $fields[] = $field;
        }

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
     * @param  stdClass $column The Column object
     * @return $string
     */
    protected function buildField($column)
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
        $title      = $this->makeTitle($name);
        $type       = strtolower($column->Type);
        $meta       = '';
        $size       = 0;
        $required   = (strtolower($column->Null) == 'no');
        $default    = $column->Default;

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

        if (starts_with($meta, '(')) {
            $size   = (int) substr($meta, 1);
            $pos    = strpos($meta, ')');
            $meta   = trim(substr($meta, $pos + 1));
        }

        $html = null;
        if (! in_array($column->Field, $ignoredFields)) {
            if ($name == 'image' || $name == 'icon')    $type = 'image';
            if ($name == 'email')                       $type = 'email';
            if ($name == 'password')                    $type = 'password';
            if ($name == 'url')                         $type = 'url';
            if (ends_with($name, '_id'))                $type = 'foreign';

            $attributes = [];
            if ($size > 0) $attributes['maxlength']     = $size;
            if ($required) $attributes['required']      = 'required';

            switch ($type) {
                case 'tinyint':
                    if ($default === '0') $defParam = ''; // "Not checked" is the default value of checkboxes
                    $html = "{{ Form::smartCheckbox('{$name}', '{$title}'{$defParam}) }}";
                    break;
                case 'int':
                    unset($attributes['maxlength']);
                    $html = "{{ Form::smartNumeric('{$name}', '{$title}'{$defParam}) }}";
                    break;
                case 'varchar':
                    $html = "{{ Form::smartText('{$name}', '{$title}'{$defParam}) }}";
                    break;
                case 'text':
                    $html = "{{ Form::smartTextarea('{$name}', '{$title}'{$defParam}) }}";
                    break;
                case 'email':
                    $html = "{{ Form::smartEmail('{$name}', '{$title}'{$defParam}) }}";
                    break;
                case 'password':
                    $html = "{{ Form::smartPassword('{$name}', '{$title}') }}";
                    break;
                case 'url':
                    $html = "{{ Form::smartUrl('{$name}', '{$title}'{$defParam}) }}";
                    break;
                case 'timestamp':
                    $html = "{{ Form::smartDateTime('{$name}', '{$title}') }}";
                    break;
                case 'image':
                    unset($attributes['maxlength']);
                    $this->fileHandling = true;
                    $html = "{{ Form::smartImageFile('{$name}', '{$title}') }}";
                    break;
                case 'foreign':
                    $title = ucfirst(substr($name, 0, -3));
                    $html = "{{ Form::smartSelectForeign('{$name}', '{$title}') }}";
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
     * Convert a snake_case sring to a title (single words, ucfirst)
     * 
     * @param  string $snakeCase The snake cased string
     * @return string
     */
    protected function makeTitle($snakeCase)
    {
        $words = explode('_', $snakeCase);

        for ($i = 0; $i < sizeof($words); $i++) $words[$i] = ucfirst($words[$i]);

        return implode(' ', $words);
    }
    
}