<?php

namespace Tourvisor\Requests;

/**
 * Class ActualizeDetailRequest
 * @see https://docs.google.com/document/d/1ertzkoJYvYT9sxbnGCVYHCbqJFr8CY4-w3A2em4rw2s/edit?usp=sharing
 *
 * @package Tourvisor\Requests
 * @property int $tourid - идентификатор тура
 * @property int $currency – валюта, в которой выдавать цену. 0 = рубли (по-умолчанию), 1 – у.е. (USD или EUR,
 *     зависит от страны), 2 – бел.рубли, 3 – тенге
 */
class ActualizeDetailRequest extends AbstractRequest
{
    protected $endPoint = 'actdetail.php';

    protected $requiredParams = ['tourid'];
}