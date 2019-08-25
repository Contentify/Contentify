<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines contain the default error messages used by
    | the validator class. Some of these rules have multiple versions such
    | as the size rules. Feel free to tweak each of these messages.
    |
    */

    'accepted'             => ':attribute deve ser aceito.',
    'active_url'           => ':attribute não é um URL válido.',
    'after'                => ':attribute deve ser uma data após a :date.',
    'after_or_equal'       => ':attribute atributo deve ser uma data após ou igual :date.',
    'alpha'                => ':attribute deve ter apenas letras.',
    'alpha_dash'           => ':attribute deve ter apenas letras, números y guiões.',
    'alpha_num'            => ':attribute deve ter apenas letras y números.',
    'array'                => ':attribute deve ser um conjunto.',
    'before'               => ':attribute deve ser uma data antes a :date.',
    'before_or_equal'      => ':attribute deve ser uma data anterior ou igual a :date.',
    'between'              => [
        'numeric' => ':attribute tem que ser entre :min - :max.',
        'file'    => ':attribute deve pesar entre :min - :max kilobytes.',
        'string'  => ':attribute tem que ter entre :min - :max carateres.',
        'array'   => ':attribute tem que ter entre :min - :max itens.',
    ],
    'boolean'              => 'O campo :attribute deve ter um valor verdadeiro ou falso.',
    'confirmed'            => 'Confirmação de :attribute não corresponde .',
    'date'                 => ':attribute não é uma data válida.',
    'date_format'          => ':attribute não corresponde ao formato :format.',
    'different'            => ':attribute e :other eles devem ser diferentes.',
    'digits'               => ':attribute deve ter :digits dígitos.',
    'digits_between'       => ':attribute deve ter entre :min e :max dígitos.',
    'dimensions'           => 'As dimensões da imagem :attribute não são válidos.',
    'distinct'             => 'O campo :attribute contém um valor duplicado.',
    'email'                => ':attribute não é um email válido',
    'exists'               => ':attribute é inválido.',
    'file'                 => 'O campo :attribute deve ser um arquivo.',
    'filled'               => 'O campo :attribute é obrigatório.',
    'gt'                   => [
        'numeric' => 'O campo :attribute deve ser maior que :value.',
        'file'    => 'O campo :attribute deve ter mais de :value kilobytes.',
        'string'  => 'O campo :attribute deve ter mais de :value caracteres.',
        'array'   => 'O campo :attribute deve ter mais de :value elementos.',
    ],
    'gte'                  => [
        'numeric' => 'O campo :attribute deve ser pelo menos :value.',
        'file'    => 'O campo :attribute deve ter pelo menos :value kilobytes.',
        'string'  => 'O campo :attribute deve ter pelo menos :value caracteres.',
        'array'   => 'O campo :attribute deve ter pelo menos :value elementos.',
    ],
    'image'                => ':attribute deve ser uma imagem.',
    'in'                   => ':attribute é inválido.',
    'in_array'             => 'O campo :attribute não existe em :other.',
    'integer'              => ':attribute deve ser um número inteiro.',
    'ip'                   => ':attribute deve ser um endereço IP válido.',
    'ipv4'                 => ':attribute deve ser um endereço IPv4 válido',
    'ipv6'                 => ':attribute deve ser um endereço IPv6 válido.',
    'json'                 => 'O campo :attribute deve ter uma sequência JSON válida.',
    'lt'                   => [
        'numeric' => 'O campo :attribute deve ser menor que :value.',
        'file'    => 'O campo :attribute deve ter menos de :value kilobytes.',
        'string'  => 'O campo :attribute deve ter menos de :value caracteres.',
        'array'   => 'O campo :attribute deve ter menos de :value elementos.',
    ],
    'lte'                  => [
        'numeric' => 'O campo :attribute deve estar no máximo :value.',
        'file'    => 'O campo :attribute deve ter no máximo :value kilobytes.',
        'string'  => 'O campo :attribute deve ter no máximo :value caracteres.',
        'array'   => 'O campo :attribute deve ter no máximo :value elementos.',
    ],
    'max'                  => [
        'numeric' => ':attribute não deve ser maior que :max.',
        'file'    => ':attribute não deve ser maior que :max kilobytes.',
        'string'  => ':attribute não deve ser maior que :max caracteres.',
        'array'   => ':attribute não deve ter mais do que :max elementos.',
    ],
    'mimes'                => ':attribute deve ser um arquivo do tipo: :values.',
    'mimetypes'            => ':attribute deve ser um arquivo do tipo: :values.',
    'min'                  => [
        'numeric' => 'O tamanho de :attribute debe ser de al menos :min.',
        'file'    => 'O tamanho de :attribute debe ser de al menos :min kilobytes.',
        'string'  => ':attribute deve conter pelo menos :min caracteres.',
        'array'   => ':attribute deve conter pelo menos :min elementos.',
    ],
    'not_in'               => ':attribute é inválido.',
    'not_regex'            => 'O formato do campo :attribute não é válido.',
    'numeric'              => ':attribute deve ser numérico.',
    'present'              => 'O campo :attribute deve estar presente.',
    'regex'                => 'O formato de :attribute não é válido.',
    'required'             => 'O campo :attribute é obrigatório.',
    'required_if'          => 'O campo :attribute é obrigatório quando :other é :value.',
    'required_unless'      => 'O campo :attribute é obrigatório a menos que :other esteja em :values.',
    'required_with'        => 'O campo :attribute é obrigatório quando :values está presente.',
    'required_with_all'    => 'O campo :attribute é obrigatório quando :values está presente.',
    'required_without'     => 'O campo :attribute é obrigatório quando :values não presente.',
    'required_without_all' => 'O campo :attribute é obrigatório quando nenhum de :values estiverem presentes.',
    'same'                 => ':attribute é :other deve corresponder.',
    'size'                 => [
        'numeric' => 'O tamanho de :attribute deve ser :size.',
        'file'    => 'O tamanho de :attribute deve ser :size kilobytes.',
        'string'  => ':attribute deve conter :size caracteres.',
        'array'   => ':attribute deve conter :size elementos.',
    ],
    'string'               => 'O campo :attribute deve ser uma sequência de caracteres.',
    'timezone'             => 'O :attribute deve ser uma zona válida.',
    'unique'               => ':attribute já foi registrado.',
    'uploaded'             => 'Upload :attribute falhou.',
    'url'                  => 'O formato :attribute é inválido.',

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Attributes
    |--------------------------------------------------------------------------
    |
    | The following language lines are used for custom validators.
    |
    */

    'alpha_numeric_spaces' => 'O campo :attribute só pode conter letras, dígitos e espaços.',
    'email_domain_allowed' => 'O campo :attribute use um domínio bloqueado.',
];
