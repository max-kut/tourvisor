<?php

namespace Tourvisor\Requests;

use Tourvisor\Exceptions\ValidateParamException;

/**
 * Class SearchResultRequest
 * @see https://docs.google.com/document/d/1nhDwzb0dPBr4MW0FJTh6TpW_U_RCSgFI7eIfvb2Gm4k/edit?usp=sharing
 *
 * @package Tourvisor\Requests
 * @property int $requestid [*] - идентификатор поискового запроса
 * @property string $type - что получаем в ответе. 'status' - только статус запроса, 'result' - результаты (туры)
 *     + статус. По умолчанию - 'result'
 * @property int $page - страница результатов поиска, которую нужно загрузить (по умолчанию = 1)
 * @property int $onpage – сколько отелей выдавать на одной странице (по умолчанию 25)
 * @property bool $nodescription – если этот параметр = 1, то краткое описание отеля не передается
 * @property bool $operatorstatus – если = 1, передает расширенный статус по операторам (показывает какие
 *     операторы были найдены, минимальная цена и количество найденных отелей по каждому туроператору)
 */
class SearchResultRequest extends AbstractRequest
{
    protected $endPoint = 'result.php';

    protected $requiredParams = [
        'requestid'
    ];

    /**
     * @param $value
     * @throws \Tourvisor\Exceptions\ValidateParamException
     */
    protected function validateTypeParam($value)
    {
        if (!in_array($value, ['status', 'result'])) {
            throw new ValidateParamException(sprintf("Not validate param 'type' in %s", static::class));
        }
    }
}