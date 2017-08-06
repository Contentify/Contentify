<?php namespace Contentify;

interface UpdaterInterface
{

    /**
     * Performs the upate
     * 
     * @return void
     */
    public function update();

    /**
     * Returns the new version number
     * 
     * @return string
     */
    public function getVersion();

}