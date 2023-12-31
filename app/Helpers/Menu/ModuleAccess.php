<?php

namespace App\Helpers\Menu;

use Illuminate\Support\Facades\DB;
use App\Interfaces\Menu\FilterInterface;
use Illuminate\Support\Facades\Auth;

class ModuleAccess implements FilterInterface
{
    /**
     * Transforms a menu item. Add the restricted property to a menu item
     * when situable.
     *
     * @param  array  $item  A menu item
     * @return array The transformed menu item
     */
    public function transform($item)
    {
        // Set a special attribute when item is not allowed. Items with this
        // attribute will be filtered out of the menu.

        if (!$this->isEnabled($item)) {
            $item['restricted'] = true;
        }

        return $item;
    }

    /**
     * Check if a menu item is allowed for the current user.
     *
     * @param  array  $item  A menu item
     * @return bool
     */
    protected function isEnabled($item)
    {
        // Check if there are any permission defined for the item.
        if (empty($item['can'])) {
            return true;
        }

        // // Check if the current user can perform the configured permissions.
        // $modules = DB::table('modules')->where('status', 'active')->get();
        // $finalModules = [];

        // if (isset($modules)) {
        //     $finalModules = $modules->pluck('id')->toArray();
        // }

        // $checkingModule = array_intersect($finalModules, $item['module_id']);
        // if (is_array($item['module_id']) && count($checkingModule) < 1) {
        //     return false;
        // }
        if (is_array($item['can'])) {
            $return = [];
            foreach ($item['can'] as $key => $permission) {
                if (Auth::check() && !Auth::user()->can($permission)) {
                    $return[$key] = false;
                } else {
                    $return[$key] = true;
                }
            }

            if (in_array(true, $return)) {
                return true;
            } else {
                return false;
            }

            dd($return);
        } else {
            if (Auth::check() && !Auth::user()->can($item['can'])) {
                return false;
            }
        }


        return true;

        return true;
    }
}
