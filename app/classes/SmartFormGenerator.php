<?php

class SmartFormGenerator {
    /**
     * Generates a simple but cms compliant form template (Blad syntax).
     * Do not blindly trust the result - most like rework is necessary.
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
     * @param  stdClass $column The Column object
     * @return $string
     */
    protected static function buildField($column)
    {
        $ignoredFields = ['id', 'access_counter', 'creator_id', 'updater_id',  'created_at', 'updated_at', 'deleted_at'];

        $name       = strtolower($column->Field);
        $title      = ucfirst($name);
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
                    if ($size > 0)
                    $html = "{{ Form::smartText('{$name}', '{$title}') }}";
                    break;
                case 'image':
                    unset($attributes['maxlength']);
                    $html = "{{ Form::smartImageFile('{$name}', '{$title}') }}";
                    break;
                case 'foreign':
                    $html = "{{ Form::smartSelect('{$name}', '{$title}') }}";
                    break;
                default:
                    $html = '<!-- Unknown type: '.$type.' -->';
                    break;
            }
            $html .= "\n";

            /*
            switch ($type) {
                case 'tinyint':
                    $html = Form::label($name, $title)."\n".Form::checkbox($name, 1, $default)."\n";
                    break;
                case 'int':
                    unset($attributes['maxlength']);
                    $attributes['class'] = 'numeric';
                    $html = Form::label($name, $title)."\n".Form::text($name, $default, $attributes)."\n";
                    break;
                case 'varchar':
                    $html = Form::label($name, $title)."\n".Form::text($name, $default, $attributes)."\n";
                    break;
                case 'email':
                    $html = Form::label($name, $title)."\n".Form::email($name, $default, $attributes)."\n";
                    break;
                case 'password':
                    $html = Form::label($name, $title)."\n".Form::password($name, $attributes)."\n";
                    break;
                case 'text':
                    $html = Form::label($name, $title)."\n".Form::textarea($name, NULL, $attributes)."\n";
                    break;
                case 'timestamp':
                    if ($size > 0)
                    $html = Form::label($name, $title)."\n".Form::text($name, $default, ['class' => 'timestamp'])."\n";
                    break;
                case 'image':
                    unset($attributes['maxlength']);
                    $html = Form::label($name, $title)."\n".Form::file($name, $attributes)."\n";
                    break;
                case 'foreign':
                    $html = Form::label($name, $title)."\n".Form::select($name, array(), NULL, $attributes)."\n";
                    break;
                default:
                    $html = '<!-- Unknown type: '.$type.' -->';
                    break;
            }
             */
        }

        return $html;
    }
}