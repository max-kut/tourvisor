<?php

namespace Tourvisor\Requests;

use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Tourvisor\Exceptions\HasEmptyRequiredParamsException;
use Tourvisor\Exceptions\NoEndPointException;
use Tourvisor\Exceptions\ValidateParamException;

abstract class AbstractRequest
{
    /**
     * @var string
     */
    protected $endPoint;

    /**
     * @var array параметры запросов
     */
    protected $params = [];

    /**
     * @var array массив ключей обязательных параметров
     */
    protected $requiredParams = [];

    /**
     * @var array массив мутаторов параметров
     * мутатор задается ассоциативным массивом, где ключ - название параметра, значение - необходимый метод
     */
    protected $casts = [];

    /**
     * AbstractRequest constructor.
     *
     * @throws \Tourvisor\Exceptions\NoEndPointException
     */
    public function __construct(array $params = [])
    {
        if (empty($this->endPoint)) {
            throw new NoEndPointException('Request ' . static::class . ' has empty endpoint');
        }
        $this->params = array_merge(['format' => 'json'], $this->params);
        foreach ($params as $key => $param) {
            $this->__set($key, $param);
        }
    }

    /**
     * @param array $params
     * @return \Tourvisor\Requests\AbstractRequest
     * @throws \Tourvisor\Exceptions\NoEndPointException
     */
    public static function create(array $params = [])
    {
        return new static($params);
    }

    /**
     * @param $name
     * @param $value
     */
    public function __set($name, $value)
    {
        if (method_exists($this, $validator = 'validate' . Str::camel($name) . 'Param')) {
            $this->$validator($value);
        }
        if (method_exists($this, $method = 'set' . Str::camel($name) . 'Param')) {
            $this->$method($value);
        } else if (isset($this->casts[$name]) && method_exists($this, $method = $this->casts[$name] . 'Mutator')) {
            $this->$method($name, $value);
        } else {
            $this->params[$name] = $value;
        }
    }

    /**
     * @param $name
     * @return mixed
     */
    public function __get($name)
    {
        return Arr::get($this->params, $name);
    }

    /**
     * мутатор для массивов
     *
     * @param $key
     * @param array $value
     */
    protected function arrayMutator($key, array $value)
    {
        $this->params[$key] = implode(',', $value);
    }

    /**
     * @param $key
     * @param string|\DateTime $value
     * @throws \Tourvisor\Exceptions\ValidateParamException
     */
    protected function dateMutator($key, $value)
    {
        if ($value instanceof \DateTime) {
            $this->params[$key] = $value->format('d.m.Y');
        } else if (is_string($value) && preg_match('/^\d{2}\.\d{2}\.\d{4}$/', $value)) {
            $this->params[$key] = $value;
        } else {
            throw new ValidateParamException(sprintf("param %s has incorrect value: %s in %s",
                $key, strval($value), static::class));
        }
    }

    /**
     * @return string
     */
    public function getEndPoint()
    {
        return $this->endPoint;
    }

    /**
     * @return array
     * @throws \Tourvisor\Exceptions\HasEmptyRequiredParamsException
     */
    public function getParams()
    {
        $this->checkRequiredParams();

        return $this->params;
    }

    /**
     * @throws \Tourvisor\Exceptions\HasEmptyRequiredParamsException
     */
    private function checkRequiredParams()
    {
        if (!empty($emptyParams = array_diff($this->requiredParams, array_keys($this->params)))) {
            throw new HasEmptyRequiredParamsException(sprintf("Params %s is required in %s",
                implode(', ', $emptyParams), static::class));
        }
    }

}