<?php

namespace Tourvisor;

use Tourvisor\Exceptions\AuthorizeException;
use Tourvisor\Requests\AbstractRequest;

class Client
{
    /**
     * @var array авторихзационные данные от ЛК tourvisor.ru
     */
    protected $authData = [
        'authlogin' => null,
        'authpass'  => null
    ];
    /** @var \GuzzleHttp\Client */
    protected $client;

    /**
     * Tourvisor constructor.
     *
     * @param string $login логин от ЛК tourvisor.ru
     * @param string $password пароль от ЛК tourvisor.ru
     */
    public function __construct($login, $password)
    {
        $this->authData['authlogin'] = $login;
        $this->authData['authpass'] = $password;
        $this->client = new \GuzzleHttp\Client([
            'base_uri' => 'http://tourvisor.ru/xml/',
        ]);
    }

    /**
     * @param \Tourvisor\Requests\AbstractRequest $request
     * @return array
     * @throws \Tourvisor\Exceptions\HasEmptyRequiredParamsException
     * @throws \Tourvisor\Exceptions\AuthorizeException
     */
    public function sendRequest(AbstractRequest $request)
    {
        $res = $this->client->get($request->getEndPoint(), [
            'query' => array_merge($this->authData, $request->getParams())
        ])->getBody()->getContents();

        if (preg_match("/Authorization Error/i", $res)) {
            throw new AuthorizeException(sprintf("Authorization Error for login %s",
                $this->authData['authlogin']));
        }

        return \GuzzleHttp\json_decode($res, true);
    }
}