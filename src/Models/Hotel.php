<?php

namespace Tourvisor\Models;

/**
 * Class Hotel
 * В этой модели достаточно много параметров приводятся к единому типу
 * К сожалению разные методы API возвращают разные название полей и разные типа данных,
 * например из запроса справочников возвращаются поля "region" и "subregion",
 * которые содержат только идентификаторы региона и субрегиона, но из запроса детальной информации по отелю
 * эти поля содержат уже названия региона и субрегиона
 *
 * @package Tourvisor\Models
 *
 * @property int $id - идентификатор отеля
 * @property string $name - название отеля
 * @property string $stars - звезность отеля
 * @property float $rating - рейтинг
 * @property int $countrycode - идентификатор страны
 * @property string $country - название страны
 * @property int $regioncode - идентификатор региона
 * @property string $region - название региона
 * @property int $subregioncode - идентификатор субрегиона
 * @property string $subregion - название субрегиона
 */
class Hotel extends AbstractModel
{
    protected $casts = [
        'id'            => 'integer',
        'countrycode'   => 'integer',
        'regioncode'    => 'integer',
        'subregioncode' => 'integer',
        'imagescount'   => 'integer',
        'price'         => 'float',
        'priceue'       => 'float',
        'rating'        => 'float',
        'coord1'        => 'float',
        'coord2'        => 'float',
        'active'        => 'boolean',
        'relax'         => 'boolean',
        'family'        => 'boolean',
        'health'        => 'boolean',
        'city'          => 'boolean',
        'deluxe'        => 'boolean',
        'isphoto'       => 'boolean',
        'isdescription' => 'boolean',
        'isreviews'     => 'boolean',
        'iscoords'      => 'boolean',
    ];

    protected $fieldAliases = [
        'hotelrating'      => 'rating',
        'hotelcode'        => 'id',
        'hotelstars'       => 'stars',
        'hotelname'        => 'name',
        'hoteldescription' => 'description',
        'countryname'      => 'country',
        'regionname'       => 'region',
        'subregionname'    => 'subregion',
    ];

    /**
     * В этот параметр прилетют значения разного типа от разных методов API
     *
     * @param $value
     */
    protected function setBeachAttribute($value)
    {
        if (preg_match('/^[01]$/', $value)) {
            $this->attributes['beach'] = boolval($value);
        } else {
            $this->attributes['beach'] = $value;
        }
    }

    /**
     * @param $value
     */
    protected function setRegionAttribute($value)
    {
        if (preg_match('/^\d+$/', $value)) {
            $this->setAttribute('regioncode', $value);
        } else {
            $this->attributes['region'] = $value;
        }
    }

    /**
     * @param $value
     */
    protected function setSubregionAttribute($value)
    {
        if (preg_match('/^\d+$/', $value)) {
            $this->setAttribute('subregioncode', $value);
        } else {
            $this->attributes['subregion'] = $value;
        }
    }

    /**
     * приведение массива изображений к нормальному виду
     * @param array $value
     */
    protected function setImagesAttribute(array $value)
    {
        if ($images = array_get($value, 'image')) {
            $this->attributes['images'] = $images;
        } else {
            $this->attributes['images'] = $value;
        }
    }
}