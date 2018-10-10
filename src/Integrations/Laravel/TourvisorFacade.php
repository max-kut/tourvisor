<?php

namespace Tourvisor\Integrations\Laravel;

use Illuminate\Support\Facades\Facade;
use Tourvisor\Requests\AbstractRequest;

/**
 * Class TourvisorFacade
 *
 * @package Tourvisor\Integrations\Laravel
 * @method static getResult(AbstractRequest $request) - получить резальтаты запроса
 */
class TourvisorFacade extends Facade
{
    /**
     * Get the registered name of the component.
     */
    public static function getFacadeAccessor()
    {
        return "tourvisor";
    }
}