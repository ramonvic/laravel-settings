<?php

namespace Umobi\Setting;

use JsonSerializable;
use Illuminate\Contracts\Support\Jsonable;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Database\Eloquent\Concerns\HasAttributes;

class Setting implements Arrayable, Jsonable, JsonSerializable
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
        return $key && array_key_exists($key, $this->attributes);
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

    public function toJson($options = 0)
    {
        return json_encode($this->jsonSerialize(), $options);
    }

    public function toArray()
    {
        return $this->attributesToArray();
    }

    public function jsonSerialize()
    {
        return $this->toArray();
    }

    public function getDates(){ return []; }
    public function getVisible(){ return []; }
    public function getHidden(){ return []; }
}
