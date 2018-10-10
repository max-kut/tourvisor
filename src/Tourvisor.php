<?php

namespace Tourvisor;

use Tourvisor\Exceptions\ResponseException;
use Tourvisor\Models\Country;
use Tourvisor\Models\Departure;
use Tourvisor\Models\Hotel;
use Tourvisor\Models\Meal;
use Tourvisor\Models\Operator;
use Tourvisor\Models\Region;
use Tourvisor\Models\SubRegion;
use Tourvisor\Models\Tour;
use Tourvisor\Requests\AbstractRequest;
use Tourvisor\Requests\ActualizeDetailRequest;
use Tourvisor\Requests\ActualizeRequest;
use Tourvisor\Requests\HotelRequest;
use Tourvisor\Requests\HotToursRequest;
use Tourvisor\Requests\ListRequest;
use Tourvisor\Requests\SearchRequest;
use Tourvisor\Requests\SearchResultRequest;

class Tourvisor
{
    /** @var \Tourvisor\Client */
    protected $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    /**
     * @param \Tourvisor\Requests\AbstractRequest $request
     * @return array
     * @throws \Tourvisor\Exceptions\AuthorizeException
     * @throws \Tourvisor\Exceptions\HasEmptyRequiredParamsException
     * @throws \Tourvisor\Exceptions\ResponseException
     */
    public function getResult(AbstractRequest $request)
    {
        return $this->transformResponse($request, $this->client->sendRequest($request));
    }

    /**
     * @param \Tourvisor\Requests\AbstractRequest $request
     * @param array $response
     * @return array|int|null|\Tourvisor\Models\AbstractModel
     * @throws \Tourvisor\Exceptions\ResponseException
     */
    protected function transformResponse(AbstractRequest $request, array $response)
    {
        switch (true) {
            case $request instanceof SearchRequest:
                return intval(array_get($response, 'result.requestid'));

            case $request instanceof SearchResultRequest:
                return [
                    'status' => array_get($response, 'data.status'),
                    'hotels' => collect(array_map([$this, 'transformHotelArray'],
                        array_get($response, 'data.result.hotel', [])))
                ];

            case $request instanceof ListRequest:
                $res = [];
                if ($departures = array_get($response, 'lists.departures.departure')) {
                    $res['departures'] = collect(array_map([$this, 'transformDepartureArray'], $departures));
                }
                if ($countries = array_get($response, 'lists.countries.country')) {
                    $res['countries'] = collect(array_map([$this, 'transformCountryArray'], $countries));
                }
                if ($regions = array_get($response, 'lists.regions.region')) {
                    $res['regions'] = collect(array_map([$this, 'transformRegionArray'], $regions));
                }
                if ($subRegions = array_get($response, 'lists.subregions.subregion')) {
                    $res['subregions'] = collect(array_map([$this, 'transformSubRegionArray'], $subRegions));
                }
                if ($meals = array_get($response, 'lists.meals.meal')) {
                    $res['meals'] = collect(array_map([$this, 'transformMealArray'], $meals));
                }
                if ($stars = array_get($response, 'lists.stars.star')) {
                    $res['stars'] = collect(array_map([$this, 'transformMealArray'], $stars));
                }
                if ($hotels = array_get($response, 'lists.hotels.hotel')) {
                    $res['hotels'] = collect(array_map([$this, 'transformHotelArray'], $hotels));
                }
                if ($operators = array_get($response, 'lists.operators.operator')) {
                    $res['operators'] = collect(array_map([$this, 'transformOperatorArray'], $operators));
                }
                if ($flydates = array_get($response, 'lists.flydates.flydate')) {
                    $res['flydates'] = collect($flydates);
                }

                return $res;

            case $request instanceof ActualizeRequest:
                if ($errorMess = array_get($response, 'error.errormessage')) {
                    throw new ResponseException($errorMess);
                }
                /** @var array|null $tour */
                if ($tour = array_get($response, 'data.tour')) {
                    // возвращаем тур с его id из запроса
                    return $this->transformTourArray(array_merge(['tourid' => $request->tourid], $tour));
                }

                return null;

            case $request instanceof ActualizeDetailRequest:
                if ($errorMess = array_get($response, 'errormessage')) {
                    throw new ResponseException($errorMess);
                }

                return $response;
            case $request instanceof HotelRequest:
                if ($errorMess = array_get($response, 'error.errormessage')) {
                    throw new ResponseException($errorMess);
                }
                if ($hotel = array_get($response, 'data.hotel')) {
                    return $this->transformHotelArray(array_merge(['hotelcode' => $request->hotelcode], $hotel));
                }

                return null;

            case $request instanceof HotToursRequest:
                return [
                    'count' => intval(array_get($response, 'hottours.hotcount')),
                    'tours' => collect(array_map([$this, 'transformTourArray'], array_get($response, 'hottours.tour', [])))
                ];
        }

        return $response;
    }

    /**
     * @param array $hotel
     * @return \Tourvisor\Models\Hotel
     */
    protected function transformHotelArray(array $hotel)
    {
        if ($hotelTours = array_get($hotel, 'tours.tour')) {
            $hotel['tours'] = collect(array_map([$this, 'transformTourArray'], $hotelTours));
        }

        return new Hotel($hotel);
    }

    /**
     * @param array $tour
     * @return \Tourvisor\Models\Tour
     */
    protected function transformTourArray(array $tour)
    {
        return new Tour($tour);
    }

    /**
     * @param array $departure
     * @return \Tourvisor\Models\Departure
     */
    protected function transformDepartureArray(array $departure)
    {
        return new Departure($departure);
    }

    /**
     * @param array $country
     * @return \Tourvisor\Models\Country
     */
    protected function transformCountryArray(array $country)
    {
        return new Country($country);
    }

    /**
     * @param array $region
     * @return \Tourvisor\Models\Region
     */
    protected function transformRegionArray(array $region)
    {
        return new Region($region);
    }

    /**
     * @param array $subRegion
     * @return \Tourvisor\Models\SubRegion
     */
    protected function transformSubRegionArray(array $subRegion)
    {
        return new SubRegion($subRegion);
    }

    /**
     * @param array $meal
     * @return \Tourvisor\Models\Meal
     */
    protected function transformMealArray(array $meal)
    {
        return new Meal($meal);
    }

    /**
     * @param array $operator
     * @return \Tourvisor\Models\Operator
     */
    protected function transformOperatorArray(array $operator)
    {
        return new Operator($operator);
    }
}