<?php namespace Contentify;
 
use Illuminate\View\Engines\CompilerEngine as OriginalCompilerEngine;

class CompilerEngine extends OriginalCompilerEngine {

    /**
     * Handle a view exception.
     * NOTICE: Overwrites the original method!
     *
     * @param  \Exception  $e
     * @param  int  $obLevel
     * @return void
     *
     * @throws $e
     */
    protected function handleViewException($e, $obLevel)
    {
        parent::handleViewException($e, $obLevel);
    }

}