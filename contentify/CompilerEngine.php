<?php

namespace Contentify;

use Exception;
use Illuminate\View\Engines\CompilerEngine as OriginalCompilerEngine;

class CompilerEngine extends OriginalCompilerEngine
{

    /**
     * Handle a view exception.
     * NOTE: Overwrites the original method!
     *
     * @param \Exception $e
     * @param int        $obLevel
     * @return void
     *
     * @throws \Exception
     */
    protected function handleViewException(Exception $e, $obLevel)
    {
        parent::handleViewException($e, $obLevel);
    }

}