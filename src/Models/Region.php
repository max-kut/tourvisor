<?php

namespace Tourvisor\Models;


class Region extends AbstractModel
{
    protected $casts = [
        'id' => 'integer',
        'country' => 'integer',
    ];
}