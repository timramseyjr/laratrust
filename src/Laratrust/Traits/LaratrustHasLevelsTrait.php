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

trait LaratrustHasLevelsTrait
{
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
        return $this->level() >= $level;
    }

    /**
     * Checks if a user has supplied level or lower
     * @param  int $level
     * @return boolean
     */
    public function hasLevelOrLess($level)
    {
        return $this->level() <= $level;
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
    /**
     * This scope allows to retrive users with an specific level
     * @param  Illuminate\Database\Eloquent\Builder $query
     * @param  int $level
     * @return Illuminate\Database\Eloquent\Builder
     */
    public function scopeWhereLevelGT($query, $level = 1)
    {
        return $query->whereHas('roles', function ($roleQuery) use ($level) {
            $roleQuery->where('level', '>=', $level);
        });
    }
    /**
     * This scope allows to retrive users with an specific level
     * @param  Illuminate\Database\Eloquent\Builder $query
     * @param  int $level
     * @return Illuminate\Database\Eloquent\Builder
     */
    public function scopeWhereLevelLT($query, $level = 1)
    {
        return $query->whereHas('roles', function ($roleQuery) use ($level) {
            $roleQuery->where('level', '<=', $level);
        });
    }
}
