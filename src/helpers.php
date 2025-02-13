<?php

use Illuminate\Database\Eloquent\Model;
use RichanFongdasen\EloquentBlameable\BlameableService;

if (!function_exists('blameable_user')) {

    /**
     * Get the blameable User identifier.
     *
     * @param \Illuminate\Database\Eloquent\Model $model
     *
     * @return int|string|null
     */
    function blameable_user(Model $model)
    {
        $guard = app(BlameableService::class)->getConfiguration($model, 'guard');

        $user = ($guard === null) ? app('auth')->user() : app('auth')->guard($guard)->user();
        $userClass = (string) app(BlameableService::class)->getConfiguration($model, 'user');

        if (($user instanceof Model) && ($user instanceof $userClass)) {
            $key = app(BlameableService::class)->getConfiguration($model, 'key') ?: '@getKey';
            $getKey = '@' === $key[0] ? substr($key, 1) : null;
            $userId = $getKey ? $user->$getKey() : $user->$key;

            return (is_int($userId) || is_string($userId)) ? $userId : null;
        }

        return null;
    }
}
