<?php

namespace Tourvisor\Models;


class Tour extends AbstractModel
{
    protected $casts = [
        'tourid'          => 'integer',
        'hotelcode'       => 'integer',
        'hotelregioncode' => 'integer',
        'countrycode'     => 'integer',
        'departurecode'   => 'integer',
        'operatorcode'    => 'integer',
        'nights'          => 'integer',
        'adults'          => 'integer',
        'child'           => 'integer',
        'detailavailable' => 'boolean',
        'price'           => 'float',
        'priceue'         => 'float',
        'priceold'        => 'float',
        'fuelcharge'      => 'float',
        'visacharge'      => 'float',
        'hotelrating'     => 'float',
    ];
}