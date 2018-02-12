<?php

namespace Unisharp\Setting;

interface SettingStorageContract
{
    /**
     * Return all data.
     *
     * @return array
     */
    public static function all();

    /**
     * Return all settings rows
     * @param null $lang
     * @return mixed
     */
    public function retrieveAll($lang = null);

    /**
     * Return setting value by key and optional by lang.
     *
     * @param string $key
     * @param string $lang
     *
     * @return string|null
     */
    public function retrieve($key, $lang = null);

    /**
     * Set the setting by key and value.
     *
     * @param string $key
     * @param mixed  $value
     * @param string $lang
     *
     * @return void
     */
    public function store($key, $value, $lang);

    /**
     * Modify a setting.
     *
     * @param string $key
     * @param string $value
     * @param string $lang
     *
     * @return bool
     */
    public function modify($key, $value, $lang);

    /**
     * Delete a setting.
     *
     * @param string $key
     * @param string $lang
     *
     * @return void
     */
    public function forget($key, $lang);
}
