<?php namespace Contentify;

use Cache, View, RuntimeException;

/**
 * Backend Navigation Generator class
 */
class BackNavGen {

    /**
     * How many navigation categories are there?
     */
    const MAX_NAVCATS = 4;

    /**
     * Name of the cache key
     */
    const CACHE_KEY = 'app.backNavTemplate';

    /**
     * Returns the navigation items
     * 
     * @return array Array with arrays of navi items
     */
    public function getItems()
    {
        $finder = app()['modules'];
        $modules = $finder->modules(); // Retrieve all module info objects

        $navItems = array();
        foreach ($modules as $module) {
            if (! $module->def('enabled')) continue;
            
            $moduleNavItems = $module->def('admin-nav'); // def() will return null if "admin-nav" is not defined
            if ($moduleNavItems) {
                $counter = 0;
                foreach ($moduleNavItems as $moduleNavItem) {
                    // Set default values for... well for everything.
                    if (! isset($moduleNavItem['url'])) {
                        $counter++;
                        if ($counter > 1) {
                            throw new RuntimeException(
                                'Module "'.$module->name().'" tries to provide two navigation items with the same URL.'
                            );
                        }
                        $moduleNavItem['url'] = 'admin/'.$module->name();
                    }
                    if (! isset($moduleNavItem['title'])) {
                        $moduleNavItem['title'] = ucfirst($module->name());
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

                    $navItems[] = $moduleNavItem;
                }
            }
        }
        return $navItems;
    }

    /**
     * Create the backend navigation, put it into a view,
     * render it and cache it.
     * @param  boolean $update Enforce cache update
     * @return void
     */
    public function make($update = false)
    {
        if (! Cache::has(self::CACHE_KEY) or $update) {
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
            Cache::forever(self::CACHE_KEY, $view->render());
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

        return Cache::get(self::CACHE_KEY);
    }

}