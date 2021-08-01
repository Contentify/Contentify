<?php

namespace Contentify\Models;

use Cartalyst\Sentinel\Permissions\StandardPermissions as OriginalStandardPermissions;
use Str;

class StandardPermissions extends OriginalStandardPermissions
{

    /**
     * See if a user has access to all of the passed permission(s).
     * This overwrites Sentinel's low-level permission system
     * and adds the level attribute.
     *
     * @param  string|array  $permissions   String of a single permission or array of multiple permissions
     * @param  int           $level         Desired level
     * @return bool
     */
    public function hasAccess($permissions, int $level = 1) : bool
    {
        if (! is_array($permissions)) {
            $permissions = (array) $permissions; // Ensure $permissions is an array
        }

        $prepared = $this->getPreparedPermissions();

        foreach ($permissions as $permission) {
            if (! $this->checkPermission($prepared, $permission, $level)) {
                return false;
            }
        }

        return true;
    }

    /**
     * See if a user has access to at least one of the passed permission(s).
     * This overwrites Sentinel's low-level permission system
     * and adds the level attribute.
     *
     * @param  string|array  $permissions   String of a single permission or array of multiple permissions
     * @param  int           $level         Desired level
     * @return bool
     */
    public function hasAnyAccess($permissions, int $level = 1) : bool
    {
        if (! is_array($permissions)) {
            $permissions = (array) $permissions; // Ensure $permissions is an array
        }

        $prepared = $this->getPreparedPermissions();

        foreach ($permissions as $permission) {
            if ($this->checkPermission($prepared, $permission, $level)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Checks a permission in the prepared array, including wildcard checks and permissions.
     * This overwrites Sentinel's low-level permission system and adds the level attribute.
     *
     * @param  array    $prepared
     * @param  string   $permission
     * @param  int      $level
     * @return bool
     */
    protected function checkPermission(array $prepared, string $permission, int $level = 1): bool
    {
        if (array_key_exists($permission, $prepared) && $prepared[$permission] >= $level) {
            return true;
        }

        foreach ($prepared as $key => $value) {
            if ((Str::is($permission, $key) || Str::is($key, $permission)) && $value >= $level) {
                return true;
            }
        }

        return false;
    }
}
