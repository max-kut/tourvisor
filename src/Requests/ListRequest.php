<?php

namespace Tourvisor\Requests;

use Tourvisor\Exceptions\ValidateParamException;

/**
 * Class ListRequest
 * @see https://docs.google.com/document/d/1sjwIbOWGkHzZmgYz39TnHEbtlWxvLYJmbKIS1hxBph4/edit?usp=sharing
 *
 * @package Tourvisor\Requests
 * @property array $type - массив типов справочников, которые нужно получить (['departure', 'country', 'region',
 *     'subregion', 'meal', 'stars', 'hotel', 'operator', 'flydate'])
 * @property int $cndep - код города вылета (в случае, если в типах есть )
 * @property int $regcountry - код страны, по которой нужно получить список курортов
 * @property int $hotcountry - код страны, по которой нужно получить список отелей (обязательно, если
 *     запрашивается тип hotel)
 * @property array $hotregion - код курорта
 * @property int $hotstars - звездность отеля (и выше)
 * @property int $hotrating - рейтинг отеля (выше или равно которому будет отображаться)
 * @property bool $hotactive - только отели "Активный"
 * @property bool $hotrelax - только отели "Спокойный"
 * @property bool $hotfamily - только отели "Семейный"
 * @property bool $hothealth - только отели "Здоровье"
 * @property bool $hotcity - только отели "Городской"
 * @property bool $hotbeach - только отели "Пляжный"
 * @property bool $hotdeluxe - только отели "Люкс (VIP)"
 * @property int $flydeparture - код города вылета (обязательно!)
 * @property int $flycountry -  код страны (обязательно!)
 */
class ListRequest extends AbstractRequest
{
    const TYPES = ['departure', 'country', 'region', 'subregion', 'meal', 'stars', 'hotel', 'operator', 'flydate'];

    protected $endPoint = 'list.php';

    protected $requiredParams = ['type'];

    protected $casts = [
        'hotregion' => 'array'
    ];

    /**
     * @param array $value
     */
    protected function setTypeParam(array $value)
    {
        if (in_array('hotel', $value)) {
            $this->requiredParams[] = 'hotcountry';
        }
        if (in_array('flydate', $value)) {
            $this->requiredParams[] = 'flydeparture';
            $this->requiredParams[] = 'flycountry';
        }
        $this->params['type'] = implode(',', $value);
    }

    /**
     * @param array $value
     * @throws \Tourvisor\Exceptions\ValidateParamException
     */
    protected function validateTypeParam(array $value)
    {
        if (!empty($wrongParameters = array_diff($value, self::TYPES))) {
            throw new ValidateParamException(sprintf("wrong item(s) in type param: %s",
                implode(',', $wrongParameters)));
        }
    }
}