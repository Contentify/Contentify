<?php namespace Contentify;

use Illuminate\Translation\Translator as OriginalTranslator;

class Translator extends OriginalTranslator {

    public function has($key, $locale = null)
    {
        if (strpos($key, '.') === false and ($pos = strpos($key, '::')) !== false) {
            $package    = substr($key, 0, $pos);
            $key        = $package.'::main.'.substr($key, $pos + 2);
        }

        return $this->get($key, array(), $locale) !== $key;
    }

    public function trans($id, array $parameters = array(), $domain = 'messages', $locale = null)
    {
        if (strpos($id, '.') === false and ($pos = strpos($id, '::')) !== false) {
            $package    = substr($id, 0, $pos);
            $id         = $package.'::main.'.substr($id, $pos + 2);
        }

        return $this->get($id, $parameters, $locale);
    }

}
