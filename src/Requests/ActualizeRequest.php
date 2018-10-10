<?php

namespace Tourvisor\Requests;

/**
 * Class ActualizeRequest
 * @see https://docs.google.com/document/d/1G--NFP7oOK4jHg272sSpXwm7yafU49-h5nFLmbC5zQA/edit?usp=sharing
 *
 * @package Tourvisor\Requests
 * @property int $tourid - идентификатор тура
 * @property int $request – производить ли фактический запрос в систему туроператора (проверку цены в системе туроператора). 0 = определять автоматически (наша система делает запрос только в случае необходимости). 1 = всегда делать запрос (принудительно).  2 = никогда не делать запроса (информация берется из базы данных). По-умолчанию = 0
 * @property-write int $currency – валюта, в которой выдавать цену. 0 = рубли (по-умолчанию), 1 – у.е. (USD или EUR, зависит от страны), 2 – бел.рубли, 3 – тенге
 */
class ActualizeRequest extends AbstractRequest
{
    protected $endPoint = 'actualize.php';

    protected $requiredParams = ['tourid'];
}