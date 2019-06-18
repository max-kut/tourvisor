<?php

namespace Tourvisor\Models;

use ArrayAccess;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use JsonSerializable;
use Illuminate\Contracts\Support\Jsonable;
use Illuminate\Contracts\Support\Arrayable;

abstract class AbstractModel implements ArrayAccess, Arrayable, Jsonable, JsonSerializable
{
    protected $attributes = [];

    protected $casts = [];

    /**
     * @var array массив алиасов названий полей
     * alias => field
     */
    protected $fieldAliases = [];

    /**
     * AbstractModel constructor.
     *
     * @param array $attributes
     */
    public function __construct(array $attributes = [])
    {
        $this->setAttributes($attributes);
    }

    /**
     * @param array $attributes
     */
    public function setAttributes(array $attributes)
    {
        foreach ($attributes as $name => $value) {
            $this->setAttribute($name, $value);
        }
    }

    /**
     * Dynamically retrieve attributes on the model.
     *
     * @param  string  $key
     * @return mixed
     */
    public function __get($key)
    {
        return $this->getAttribute($key);
    }

    /**
     * Dynamically set attributes on the model.
     *
     * @param  string  $key
     * @param  mixed  $value
     * @return void
     */
    public function __set($key, $value)
    {
        $this->setAttribute($key, $value);
    }

    /**
     * @param $name
     * @return mixed|null
     */
    public function getAttribute($name)
    {
        if (isset($this->attributes[$name])) {
            return $this->attributes[$name];
        }

        return null;
    }

    /**
     * @param $name
     * @param $value
     */
    public function setAttribute($name, $value)
    {
        if(isset($this->fieldAliases[$name])){
            $name = $this->fieldAliases[$name];
        }
        if (method_exists($this, $setter = 'set' . Str::camel($name) . 'Attribute')) {
            $this->$setter($value);
        } else if (isset($this->casts[$name]) && method_exists($this, $setter = $this->casts[$name] . 'Mutator')) {
            $this->$setter($name, $value);
        } else {
            $this->attributes[$name] = $value;
        }
    }

    /**
     * @param $name
     * @param $value
     */
    protected function arrayMutator($name, $value)
    {
        $this->attributes[$name] = (array)$value;
    }

    /**
     * @param $name
     * @param $value
     */
    protected function integerMutator($name, $value)
    {
        $this->attributes[$name] = intval($value);
    }

    /**
     * @param $name
     * @param $value
     */
    protected function floatMutator($name, $value)
    {
        $this->attributes[$name] = floatval($value);
    }

    /**
     * @param $name
     * @param $value
     */
    protected function booleanMutator($name, $value)
    {
        $this->attributes[$name] = boolval($value);
    }

    /**
     * @param int $options
     * @return false|string
     */
    public function toJson($options = 0)
    {
        return json_encode($this->toArray());
    }

    /**
     * Specify data which should be serialized to JSON
     *
     * @link https://php.net/manual/en/jsonserializable.jsonserialize.php
     * @return mixed data which can be serialized by <b>json_encode</b>,
     * which is a value of any type other than a resource.
     * @since 5.4.0
     */
    public function jsonSerialize()
    {
        return $this->toArray();
    }

    /**
     * @return array
     */
    public function toArray()
    {
        return collect($this->attributes)->mapWithKeys(function($value, $key){
            $value = $value instanceof Collection ? $value->toArray() : $value;
            return [$key => $value];
        })->toArray();
    }

    /**
     * Determine if the given attribute exists.
     *
     * @param  mixed  $offset
     * @return bool
     */
    public function offsetExists($offset)
    {
        return ! is_null($this->getAttribute($offset));
    }

    /**
     * Get the value for a given offset.
     *
     * @param  mixed  $offset
     * @return mixed
     */
    public function offsetGet($offset)
    {
        return $this->getAttribute($offset);
    }

    /**
     * Set the value for a given offset.
     *
     * @param  mixed  $offset
     * @param  mixed  $value
     * @return void
     */
    public function offsetSet($offset, $value)
    {
        $this->setAttribute($offset, $value);
    }

    /**
     * Unset the value for a given offset.
     *
     * @param  mixed  $offset
     * @return void
     */
    public function offsetUnset($offset)
    {
        unset($this->attributes[$offset]);
    }

    /**
     * Determine if an attribute or relation exists on the model.
     *
     * @param  string  $key
     * @return bool
     */
    public function __isset($key)
    {
        return $this->offsetExists($key);
    }

    /**
     * Unset an attribute on the model.
     *
     * @param  string  $key
     * @return void
     */
    public function __unset($key)
    {
        $this->offsetUnset($key);
    }

    /**
     * Convert the model to its string representation.
     *
     * @return string
     */
    public function __toString()
    {
        return $this->toJson();
    }
}