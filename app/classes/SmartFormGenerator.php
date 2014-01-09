<?php

class SmartFormGenerator {
    /**
     * Generates a simple but cms compliant form template (Blad syntax).
     * Do not blindly trust the result - most like rework is necessary.
     * 
     * @param  string $tableName  The table (representing a model) to generate a form for
     * @param  string $moduleName The module name - leave it empty if its the pluralized model name
     * @return string
     */
    public static function generate($tableName, $moduleName = NULL)
    {

        if ($moduleName == NULL) $moduleName = $tableName;

        $columns = DB::select('SHOW COLUMNS FROM '.$tableName);

        $fields = array();
        foreach ($columns as $columndIndex => $column) {
            $field = self::buildField($column);
            if ($field) $fields[] = $field;
        }

        $formView = View::make('smartform.template', ['fields' => $fields, 'modulename' => $moduleName]);

        return $formView->render();
    }

    /**
     * Creates a single form field.
     * Returns NULL if the field is ignored.
     * 
     * @param  stdClass $column The Column object
     * @return $string
     */
    protected static function buildField($column)
    {
        $ignoredFields = ['id', 'access_counter', 'creator_id', 'updater_id',  'created_at', 'updated_at', 'deleted_at'];

        $name       = strtolower($column->Field);
        $title      = self::makeTitle($name);
        $type       = strtolower($column->Type);
        $meta       = '';
        $size       = 0;
        $required   = (strtolower($column->Null) == 'no');
        $default    = $column->Default;

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

        $html = NULL;
        if (! in_array($column->Field, $ignoredFields)) {
            if ($name == 'image') $type = 'image';
            if ($name == 'email') $type = 'email';
            if ($name == 'password') $type = 'password';
            if (ends_with($name, '_id')) $type = 'foreign';

            $attributes = [];
            if ($size > 0) $attributes['maxlength'] = $size;
            if ($required) $attributes['required'] = 'required';

            switch ($type) {
                case 'tinyint':
                    $html = "{{ Form::smartCheckbox('{$name}', '{$title}') }}";
                    break;
                case 'int':
                    unset($attributes['maxlength']);
                    $attributes['class'] = 'numeric';
                    $html = "{{ Form::smartText('{$name}', '{$title}') }}";
                    break;
                case 'varchar':
                    $html = "{{ Form::smartText('{$name}', '{$title}') }}";
                    break;
                case 'email':
                    $html = "{{ Form::smartEmail('{$name}', '{$title}') }}";
                    break;
                case 'password':
                    $html = "{{ Form::smartPassword('{$name}', '{$title}') }}";
                    break;
                case 'text':
                    $html = "{{ Form::smartTextarea('{$name}', '{$title}') }}";
                    break;
                case 'timestamp':
                    $html = "{{ Form::smartText('{$name}', '{$title}') }}";
                    break;
                case 'image':
                    unset($attributes['maxlength']);
                    $html = "{{ Form::smartImageFile('{$name}', '{$title}') }}";
                    break;
                case 'foreign':
                    $title = ucfirst(substr($name, 0, -3));
                    $html = "{{ Form::smartSelectForeign('{$name}', '{$title}') }}";
                    break;
                default:
                    $html = '<!-- Unknown type: '.$type.' -->';
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
    protected static function makeTitle($snakeCase)
    {
        $words = explode('_', $snakeCase);

        for ($i = 0; $i < sizeof($words); $i++) $words[$i] = ucfirst($words[$i]);

        return implode(' ', $words);
    }
}