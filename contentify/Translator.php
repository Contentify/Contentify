<?php

namespace Contentify;

use Illuminate\Translation\Translator as OriginalTranslator;

class Translator extends OriginalTranslator
{

    /**
     * {@inheritdoc}
     */
    public function has($key, $locale = null, $fallback = true)
    {
        if (strpos($key, '.') === false and ($pos = strpos($key, '::')) !== false) {
            $package  = substr($key, 0, $pos);
            $key      = $package.'::main.'.substr($key, $pos + 2);
        }

        return $this->get($key, array(), $locale, $fallback) !== $key;
    }

    /**
     * {@inheritdoc}
     */
    public function trans($id, array $parameters = array(), $domain = 'messages', $locale = null)
    {
        if (strpos($id, '.') === false and ($pos = strpos($id, '::')) !== false) {
            $package  = substr($id, 0, $pos);
            $id       = $package.'::main.'.substr($id, $pos + 2);
        }

        return $this->get($id, $parameters, $locale);
    }

    /**
     * Make the place-holder replacements on a line.
     * NOTE: The original method will sometimes capitalize values - 
     * we do not like that.
     *
     * @param string $line
     * @param array  $replace
     * @return string
     */
    protected function makeReplacements($line, array $replace)
    {
        $replace = $this->sortReplacements($replace);

        foreach ($replace as $key => $value)
        {
            $line = str_replace(':'.$key, $value, $line);
        }

        return $line;
    }

}
