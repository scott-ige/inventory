<?php

namespace Stevebauman\Inventory\Traits;

use Stevebauman\Inventory\InventoryServiceProvider;
use Stevebauman\Inventory\Exceptions\NoUserLoggedInException;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Lang;

/**
 * Trait UserIdentificationTrait.
 */
trait UserIdentificationTrait
{
    /**
     * Attempt to find the user id of the currently logged in user
     * Supports Cartalyst Sentry/Sentinel based authentication, as well as stock Auth.
     *
     * Thanks to https://github.com/VentureCraft/revisionable/blob/master/src/Venturecraft/Revisionable/RevisionableTrait.php
     *
     * @throws NoUserLoggedInException
     *
     * @return int|string|null
     */
    protected static function getCurrentUserId()
    {
        /*
         * Accountability is enabled, let's try and retrieve the current users ID
         */
        try {
            if (class_exists($class = '\Cartalyst\Sentry\Facades\Laravel\Sentry') || class_exists($class = '\Cartalyst\Sentinel\Laravel\Facades\Sentinel')) {
                if ($class::check()) {
                    return $class::getUser()->id;
                }
            } elseif (class_exists('Illuminate\Auth') || class_exists('Illuminate\Support\Facades\Auth')) {
                if (\Auth::check()) {
                    return \Auth::user()->getAuthIdentifier();
                }
            }
        } catch (\Exception $e) {
        }

        // Check if no user is allowed
        if (Config::get('inventory.' . 'allow_no_user')) {
            return;
        }

        /*
         * Couldn't get the current logged in users ID and and no user is not allowed, throw exception
         */
        $message = Lang::get('inventory::exceptions.NoUserLoggedInException');

        throw new NoUserLoggedInException($message);
    }

    /**
     * Get the foreign user key.
     *
     * @return mixed
     */
    protected static function getForeignUserKey()
    {
        return config('inventory.' . 'foreign_user_key', 'created_by');
    }
}
