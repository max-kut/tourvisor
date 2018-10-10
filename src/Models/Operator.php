<?php

namespace Tourvisor\Models;


class Operator extends AbstractModel
{
    protected $casts = [
        'id'            => 'integer',
        'onlinebooking' => 'boolean',
    ];
}