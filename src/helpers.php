<?php

if (! function_exists('setting')) {
    /**
     * Return one or all settings
     *
     * @param  string|null  $key
     * @return mixed
     * */
    function setting($key = null, $default = null) {
        return \Setting::get($key) ?? $default;
    }
}