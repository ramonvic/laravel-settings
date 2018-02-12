<?php

namespace Unisharp\Setting;

use Illuminate\Database\Eloquent\Concerns\HasAttributes;

class Setting
{
    public static $castMap = [];

    use HasAttributes;

    protected $lang = null;
    protected $autoResetLang = true;

    protected $storage = null;

    public function __construct(SettingStorageContract $storage)
    {
        $this->storage = $storage;
        if (\Schema::hasTable('settings')) {
            $this->load();
        }
    }

    private function load()
    {
        $settings = $this->storage->retrieveAll($this->lang);
        $attributes = [];
        foreach ($settings as $setting) {
            $value = json_decode($setting->value, true);
            if (!is_array($value)) {
                $value = $setting->value;
            }
            array_set($attributes, $setting->key, $value);
        }

        $attributes = array_dot($attributes);

        foreach ($attributes as $key=>$value) {
            $this->setAttribute($key, $value);
        }
        $this->syncOriginal();
    }

    /**
     * Check if the setting exists.
     *
     * @param string $key
     *
     * @return bool
     */
    public function has($key)
    {
        $exists = $this->has($key);

        return $exists;
    }

    public function get($key = null)
    {
        if (!is_null($key)) {
            return $this->getAttribute($key);
        }
        return $this->attributesToArray();
    }

    public function set($key, $value)
    {
        $this->setAttribute($key, $value);
    }

    public function __get($name)
    {
        return $this->getAttribute($name);
    }

    public function __set($key, $value)
    {
        $this->set($key, $value);
    }

    public function save() {
        foreach ($this->getDirty() as $key=>$value) {
            if ($this->storage->retrieve($key, $this->lang)) {
                $this->storage->modify($key, $value, $this->lang);
            } else {
                $this->storage->store($key, $value, $this->lang);
            }
        }
        $this->syncOriginal();
    }

    public static function castMap(array $map = null, $merge = true)
    {
        if (is_array($map)) {
            static::$castMap = $merge && static::$castMap
                ? $map + static::$castMap : $map;
        }
        return static::$castMap;
    }

    public function getCasts()
    {
        return static::$castMap ?? [];
    }

    public function getAttribute($key)
    {
        if ($key && array_key_exists($key, $this->attributes) ||
            $this->hasGetMutator($key)) {
            return $this->getAttributeValue($key);
        }
        return null;
    }

    public function toJson()
    {
        return \GuzzleHttp\json_encode($this->attributesToArray());
    }

    public function getDates(){ return []; }
    public function getVisible(){ return []; }
    public function getHidden(){ return []; }
}
