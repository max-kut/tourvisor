<?php

namespace Tourvisor\Requests;

/**
 * Class SearchRequest
 *
 * @see https://docs.google.com/document/d/1nhDwzb0dPBr4MW0FJTh6TpW_U_RCSgFI7eIfvb2Gm4k/edit?usp=sharing
 *
 * @package Tourvisor\Requests
 *
 * @property int $departure – код города вылета
 * @property int $country – код страны
 * @property string $datefrom - дата от в формате d.m.Y (по умолчанию текущая дата +1 день)
 * @property string $dateto - дата до в формате d.m.Y (по умолчанию текущая дата +8 дней). Максимальный диапазон
 *     14 дней
 * @property int $nightsfrom - ночей от (по умолчанию = 7)
 * @property int $nightsto - ночей до (по умолчанию = 10)
 * @property int $adults - кол-во взрослых (по умолчанию = 2)
 * @property int $child - кол-во детей (по умолчанию = 0)
 * @property int $childage1 - возраст 1 ребенка, лет (опционально). Младенец = 1
 * @property int $childage2 - возраст 2 ребенка, лет (опционально). Младенец = 1
 * @property int $childage3 - возраст 3 ребенка, лет (опционально). Младенец = 1
 * @property int $stars – категория отеля (звездность) (опционально)
 * @property bool $starsbetter – 1 – показывать категории лучше указанной. по умолчанию 1 (опционально)
 * @property int $meal - тип питания (код) (опционально)
 * @property bool $mealbetter – 1 – показывать питание лучше указанного. по умолчанию 1 (опционально)
 * @property int $rating – рейтинг отеля (опционально). Используется кодировка: 0: любой, 2: >= 3.0, 3: >= 3.5,
 *     4: >= 4.0, 5: >= 4.5  (т.е. нужно передать целое число, соотв. критерию)
 * @property array $hotels - коды отелей
 * @property array $hoteltypes - типы отелей (массив со следующими значениями: active, relax, family, health, city,
 *     beach, deluxe) пример:['relax','beach']  (опционально)
 * @property int $pricetype - тип цены. 0 – цена за номер, 1 – цена за человека (по умолчанию 0)
 * @property array $regions – коды курортов (опционально)
 * @property array $subregions – коды вложенных курортов (районов) (опционально). Если Вам нужен поиск по
 *     конкретному району (subregion), то соответствующий ему параметр regions указывать не нужно, иначе будет
 *     производиться поиск по всему курорту (region), который Вы указали
 * @property array $operators - массив операторов (опционально)
 * @property int $pricefrom - цена от (в рублях, опционально)
 * @property int $priceto - цена до (в рублях, опционально)
 * @property int $currency – валюта, в которой производить выдачу результатов поиска. 0 = рубли (по-умолчанию), 1
 *     – у.е. (USD или EUR, зависит от страны), 2 – бел.рубли, 3 – тенге
 */
class SearchRequest extends AbstractRequest
{
    protected $endPoint = 'search.php';

    protected $casts = [
        'hotels'     => 'array',
        'hoteltypes' => 'array',
        'regions'    => 'array',
        'subregions' => 'array',
        'operators'  => 'array',
    ];

    protected $requiredParams = [
        'country', 'departure'
    ];
}