<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | Il following language lines contain the default error messages used by
    | the validator class. Some of these rules have multiple versions such
    | as the size rules. Feel free to tweak each of these messages here.
    |
    */

    'accettato'             => 'Il valore di :attribute deve essere accettato.',
    'active_url'           => 'Il valore di :attribute non è un valido URL.',
    'after'                => 'Il valore di :attribute deve essere una data dopo :date.',
    'after_or_equal'       => 'Il valore di :attribute deve essere una data dopo o uguale a :date.',
    'alpha'                => 'Il valore di :attribute può solo contenere lettere.',
    'alpha_dash'           => 'Il valore di :attribute può solo contenere lettere, numeri, e tratti.',
    'alpha_num'            => 'Il valore di :attribute può solo contenere lettere e numeri.',
    'array'                => 'Il valore di :attribute deve essere un array.',
    'before'               => 'Il valore di :attribute deve essere una data prima :date.',
    'before_or_equal'      => 'Il valore di :attribute deve essere una data prima o uguale a :date.',
    'between'              => [
        'numeric' => 'Il valore di :attribute deve essere tra :min e :max.',
        'file'    => 'Il valore di :attribute deve essere tra :min e :max kilobytes.',
        'string'  => 'Il valore di :attribute deve essere tra :min e :max caratteri.',
        'array'   => 'Il valore di :attribute deve avere tra :min e :max oggetti.',
    ],
    'boolean'              => 'Il valore del campo :attribute deve essere vero o falso.',
    'confirmed'            => 'Il valore di :attribute di conferma non corrisponde.',
    'date'                 => 'Il valore di :attribute non è una valida data.',
    'date_format'          => 'Il valore di :attribute non corrisponde al formato :format.',
    'different'            => 'Il valore di :attribute e :other deve essere diverso.',
    'digits'               => 'Il valore di :attribute deve avere :digits decimali.',
    'digits_between'       => 'Il valore di :attribute deve essere tra :min e :max deciamli.',
    'dimensions'           => 'Il valore di :attribute ha dimensioni dell\'immagine sbagliate.',
    'distinct'             => 'Il valore del campo :attribute contiene valori duplicati.',
    'email'                => 'Il valore di :attribute deve essere un valido indirizzo email.',
    'exists'               => 'Il valore selezionato :attribute non è valido.',
    'file'                 => 'Il valore di :attribute deve essere un file.',
    'filled'               => 'Il valore del campo :attribute deve avere un valore.',
    'image'                => 'Il valore di :attribute deve essere un\'immagine.',
    'in'                   => 'Il valore selezionato :attribute non è valido.',
    'in_array'             => 'Il valore del campo :attribute non deve esistere in :other.',
    'integer'              => 'Il valore di :attribute deve essere un numero intero.',
    'ip'                   => 'Il valore di :attribute deve essere un valido indirizzo IP.',
    'json'                 => 'Il valore di :attribute deve essere una valida stringa JSON.',
    'max'                  => [
        'numeric' => 'Il valore di :attribute non dovrebbe essere più grande di :max.',
        'file'    => 'Il valore di :attribute non dovrebbe essere più grande di :max kilobytes.',
        'string'  => 'Il valore di :attribute non dovrebbe avere più grande di :max caratteri.',
        'array'   => 'Il valore di :attribute non dovrebbe avere più di :max oggetti.',
    ],
    'mimes'                => 'Il valore di :attribute deve essere un file di tipo: :values.',
    'mimetypes'            => 'Il valore di :attribute deve essere un file di tipo: :values.',
    'min'                  => [
        'numeric' => 'Il valore di :attribute deve essere almeno :min.',
        'file'    => 'Il valore di :attribute deve essere almeno :min kilobytes.',
        'string'  => 'Il valore di :attribute deve avere almeno :min caratteri.',
        'array'   => 'Il valore di :attribute deve avere almeno :min oggetti.',
    ],
    'not_in'               => 'Il valore selezionato :attribute non è valido.',
    'numeric'              => 'Il valore di :attribute deve essere un numero.',
    'present'              => 'Il valore del campo :attribute deve essere presente.',
    'regex'                => 'Il formato di :attribute non è valido.',
    'required'             => 'Il valore del campo :attribute è richiesto.',
    'required_if'          => 'Il valore del campo :attribute è richiesto quando :other è :value.',
    'required_unless'      => 'Il valore del campo :attribute è richiesto se :other non è in :values.',
    'required_with'        => 'Il valore del campo :attribute è richiesto quando :values è presente.',
    'required_with_all'    => 'Il valore del campo :attribute è richiesto quando :values è presente.',
    'required_without'     => 'Il valore del campo :attribute è richiesto quando :values non è presente.',
    'required_without_all' => 'Il valore del campo :attribute è richiesto quando nessuno dei :values è presente.',
    'same'                 => 'Il valore di :attribute e :other deve essere uguale.',
    'size'                 => [
        'numeric' => 'Il valore di :attribute deve essere :size.',
        'file'    => 'Il valore di :attribute deve essere :size kilobytes.',
        'string'  => 'Il valore di :attribute deve avere :size caratteri.',
        'array'   => 'Il valore di :attribute deve contenere :size oggetti.',
    ],
    'string'               => 'Il valore di :attribute deve essere una stringa.',
    'timezone'             => 'Il valore di :attribute deve essere un valido timezone.',
    'unique'               => 'Il valore di :attribute è stato gia usato.',
    'uploaded'             => 'Il file :attribute non è stato caricato.',
    'url'                  => 'Il formato di :attribute non è valido.',

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Attributes
    |--------------------------------------------------------------------------
    |
    | Il following language lines are used for custom validators.
    |
    */

    'alpha_numeric_spaces' => 'Il valore di :attribute può solo contenere lettere, numeri e spazi.',
    'email_domain_allowed' => ':attribute utilzza un dominio bloccato.',

];