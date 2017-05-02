<?php

namespace Laratrust\Traits;

/**
 * This file is part of Laratrust,
 * a role & permission management solution for Laravel.
 *
 * @license MIT
 * @package Laratrust
 */

use Illuminate\Support\Facades\Config;
use InvalidArgumentException;

trait LaratrustHasLevelsTrait
{
    /**
     * Many-to-Many relations with Role.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function roles()
    {
        return $this->morphToMany(
            Config::get('laratrust.role'),
            'user',
            Config::get('laratrust.role_user_table'),
            Config::get('laratrust.user_foreign_key'),
            Config::get('laratrust.role_foreign_key')
        );
    }

    /**
     * Get role level of a user.
     *
     * @return int
     */
    public function level()
    {
        return ($role = $this->roles()->orderBy('level', Config::get('laratrust.level_sort'))->first()) ? $role->level : 0;
    }

    /**
     * Checks if a user has supplied level or higher
     * @param  int $level
     * @return boolean
     */
    public function hasLevelOrGreater($level)
    {
        return $level >= $this->level();
    }

    /**
     * Checks if a user has supplied level or lower
     * @param  int $level
     * @return boolean
     */
    public function hasLevelOrLesser($level)
    {
        return $level <= $this->level();
    }

    /**
     * Checks if a user has a level between the two supplied
     * @param  string $levels
     * @return boolean
     */
    public function hasLevelBetween($levels)
    {
        if(strpos($levels,'^') === false || count($split = explode('^',$levels)) < 2){
            return false;
        }
        return $this->level() >= $split[0]  && $this->level() <= $split[1];
    }
}
