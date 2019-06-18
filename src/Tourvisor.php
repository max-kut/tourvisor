<?php

namespace Tourvisor;

use Illuminate\Support\Arr;
use Tourvisor\Exceptions\ResponseException;
use Tourvisor\Models\Country;
use Tourvisor\Models\Departure;
use Tourvisor\Models\Hotel;
use Tourvisor\Models\Meal;
use Tourvisor\Models\Operator;
use Tourvisor\Models\Region;
use Tourvisor\Models\Review;
use Tourvisor\Models\Star;
use Tourvisor\Models\SubRegion;
use Tourvisor\Models\Tour;
use Tourvisor\Requests\AbstractRequest;
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
        if ($errorMess = Arr::get($response, 'error.errormessage')) {
            throw new ResponseException($errorMess);
        }
        switch (true) {
            case $request instanceof SearchRequest:
                return intval(Arr::get($response, 'result.requestid'));
            case $request instanceof SearchResultRequest:
                return [
                    'status' => Arr::get($response, 'data.status'),
                    'hotels' => collect(array_map([$this, 'transformHotelArray'],
                        Arr::get($response, 'data.result.hotel', [])))
                ];
            case $request instanceof ListRequest:
                $res = [];
                if ($departures = Arr::get($response, 'lists.departures.departure')) {
                    $res['departures'] = collect(array_map([$this, 'transformDepartureArray'], $departures));
                }
                if ($countries = Arr::get($response, 'lists.countries.country')) {
                    $res['countries'] = collect(array_map([$this, 'transformCountryArray'], $countries));
                }
                if ($regions = Arr::get($response, 'lists.regions.region')) {
                    $res['regions'] = collect(array_map([$this, 'transformRegionArray'], $regions));
                }
                if ($subRegions = Arr::get($response, 'lists.subregions.subregion')) {
                    $res['subregions'] = collect(array_map([$this, 'transformSubRegionArray'], $subRegions));
                }
                if ($meals = Arr::get($response, 'lists.meals.meal')) {
                    $res['meals'] = collect(array_map([$this, 'transformMealArray'], $meals));
                }
                if ($stars = Arr::get($response, 'lists.stars.star')) {
                    $res['stars'] = collect(array_map([$this, 'transformStarArray'], $stars));
                }
                if ($hotels = Arr::get($response, 'lists.hotels.hotel')) {
                    $res['hotels'] = collect(array_map([$this, 'transformHotelArray'], $hotels));
                }
                if ($operators = Arr::get($response, 'lists.operators.operator')) {
                    $res['operators'] = collect(array_map([$this, 'transformOperatorArray'], $operators));
                }
                if ($flydates = Arr::get($response, 'lists.flydates.flydate')) {
                    $res['flydates'] = collect($flydates);
                }

                return $res;
            case $request instanceof ActualizeRequest:
                /** @var array|null $tour */
                if ($tour = Arr::get($response, 'data.tour')) {
                    // возвращаем тур с его id из запроса
                    return $this->transformTourArray(array_merge(['tourid' => $request->tourid], $tour));
                }

                return null;
            case $request instanceof HotelRequest:
                if ($hotel = Arr::get($response, 'data.hotel')) {
                    return $this->transformHotelArray(array_merge(['hotelcode' => $request->hotelcode], $hotel));
                }

                return null;
            case $request instanceof HotToursRequest:
                return [
                    'count' => intval(Arr::get($response, 'hottours.hotcount')),
                    'tours' => collect(array_map([$this, 'transformTourArray'], Arr::get($response, 'hottours.tour', [])))
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
        if ($hotelTours = Arr::get($hotel, 'tours.tour')) {
            $hotel['tours'] = collect(array_map([$this, 'transformTourArray'], $hotelTours));
        }
        if ($hotelReviews = Arr::get($hotel, 'reviews.review')) {
            $hotel['reviews'] = collect(array_map([$this, 'transformReviewArray'], $hotelReviews));
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
     * @param array $star
     * @return \Tourvisor\Models\Star
     */
    protected function transformStarArray(array $star)
    {
        return new Star($star);
    }

    /**
     * @param array $operator
     * @return \Tourvisor\Models\Operator
     */
    protected function transformOperatorArray(array $operator)
    {
        return new Operator($operator);
    }

    /**
     * @param array $review
     * @return \Tourvisor\Models\Review
     */
    protected function transformReviewArray(array $review)
    {
        return new Review($review);
    }
}