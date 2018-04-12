<?php

namespace Contentify;

use Cache;
use File;
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
    public function trans($id, array $parameters = array(), $locale = null)
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

    /**
     * Returns the codes of all available languages.
     * ATTENTION: Caches the languages for some time, so if you add a new language
     * you have to wait a little bit of clear the cache!
     *
     * @return string[]
     */
    public function languageCodes()
    {
        $languageCodes = Cache::get('app.locales');

        if ($languageCodes === null) {
            // Use the names of the language directories to identify the supported languages
            $languageCodes = File::directories( base_path().'/resources/lang');

            // Only keep the name of the directory instead of the whole path
            array_walk($languageCodes, function(&$value, $key)
            {
                $value = basename($value);
            });

            Cache::put('app.locales', $languageCodes,10);
        }

        return $languageCodes;
    }

}
