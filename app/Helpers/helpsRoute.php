<?php
/**
 * Created by PhpStorm.
 * User: Jackson
 * Date: 17/01/2018
 * Time: 1:00 PM
 */

namespace App\Helpers;


class helpsRoute
{


    public static function activeMenu($url)
    {
        return request()->is($url) ? 'active' : '';
    }

}