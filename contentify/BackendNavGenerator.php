<?php

namespace Contentify;

use Cache;
use RuntimeException;
use View;

/**
 * Backend navigation generator class - generates the navigation of the backend and caches it
 */
class BackendNavGenerator
{

    /**
     * How many navigation categories are there?
     */
    const MAX_NAVCATS = 4;

    /**
     * Name of the cache key
     */
    const CACHE_KEY = 'app.backNavTemplate';

    /**
     * The current locale, for example 'en'
     *
     * @var string
     */
    protected $locale = null;

    public function __construct()
    {
        $session = app('session');
        $this->locale = $session->get('app.locale');
    }

    /**
     * Returns the navigation items
     * 
     * @return mixed[][] Array with arrays of navigation items
     */
    public function getItems()
    {
        $translator = app('translator');
        $moduleBase = app()['modules'];
        $modules = $moduleBase->all(); // Retrieve all module info objects

        $navItems = array();
        foreach ($modules as $module) {
            if (! $module['enabled']) continue;

            if (isset($module['admin-nav'])) {
                $moduleNavItems = $module['admin-nav'];

                $counter = 0;
                foreach ($moduleNavItems as $moduleNavItem) {
                    /*
                     * Set default values for... well for everything.
                     */
                    if (! isset($moduleNavItem['url'])) {
                        $counter++;
                        if ($counter > 1) {
                            throw new RuntimeException(
                                'Module "'.$module['slug'].'" tries to provide two navigation items with the same URL.'
                            );
                        }
                        $moduleNavItem['url'] = 'admin/'.strtolower($module['slug']);
                    }
                    if (! isset($moduleNavItem['title'])) {
                        $moduleNavItem['title'] = $module['slug'];
                    }
                    if (! isset($moduleNavItem['position'])) {
                        $moduleNavItem['position'] = 999;
                    }
                    if (! isset($moduleNavItem['category'])) {
                        $moduleNavItem['category'] = 1;
                    }
                    if (! isset($moduleNavItem['icon'])) {
                        $moduleNavItem['icon'] = 'newspaper.png';
                    }

                    if (! isset($moduleNavItem['translate']) or $moduleNavItem['translate'] === false) {
                        $key = 'app.object_'.snake_case($moduleNavItem['title']);
                        if ($translator->has($key)) {
                            $moduleNavItem['title'] = $translator->get($key);
                        }
                    } else {
                        $key = $moduleNavItem['translate'];
                        if ($translator->has($key)) {
                            $moduleNavItem['title'] = $translator->get($key);
                        }
                    }

                    $navItems[] = $moduleNavItem;
                }
            }
        }
        return $navItems;
    }

    /**
     * Create the backend navigation, put it into a view, render it and cache it.
     *
     * @param bool $update Enforce cache update?
     * @return void
     */
    public function make($update = false)
    {
        if (! Cache::has(self::CACHE_KEY.'_'.$this->locale) or $update) {
            $navItems = $this->getItems();
            $navCategories = array();

            for ($i = 1; $i <= self::MAX_NAVCATS; $i++) { 
                foreach ($navItems as $navItem) {
                    if ($navItem['category'] == $i) {
                        $navCategories[$i][$navItem['position']] = $navItem;
                    }
                }

                ksort($navCategories[$i]); // Sort category array to bring positions in the right order
            }

            $view = View::make('backend.navigation', compact('navCategories'));
            Cache::forever(self::CACHE_KEY.'_'.$this->locale, $view->render());
        }
    }

    /**
     * Enforce (cache) update
     * 
     * @return void
     */
    public function update()
    {
        $this->make(true);
    }

    /**
     * Returns the HTML code of the backend navigation
     * 
     * @return string The code
     */
    public function get()
    {
        $this->make();

        return Cache::get(self::CACHE_KEY.'_'.$this->locale);
    }

}