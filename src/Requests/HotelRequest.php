<?php

namespace Tourvisor\Requests;

/**
 * Class HotelRequest
 * @see https://docs.google.com/document/d/1jWLPpGeMrIxo1aGtLwI8fvlx1MBZutZ3TVPRkVFBA9Y/edit?usp=sharing
 *
 * @package Tourvisor\Requests
 * @property int $hotelcode - код отеля (можно получить из результатов поиска или из справочника отелей).
 *     Указывается обязательно!
 * @property int $imgwidth – ширина фотографий отеля  (необязательно)
 * @property int $imgheight – высота фотографий отеля (необязательно)
 * @property bool $removetags – убирать HTML теги из списков. По умолчанию списки (например, список услуг в
 *     отеле) передаются с HTML тегами, которые формируют список (LI). Если задать параметр removetags=1, то вместо
 *     HTML тегов элементы списка разделяются просто точкой с запятой.
 * @property bool $reviews – если этот параметр = 1, то в выдачу включаются также отзывы по отелю (при их
 *     наличии).
 */
class HotelRequest extends AbstractRequest
{
    protected $endPoint = 'hotel.php';

    protected $requiredParams = ['hotelcode'];
}