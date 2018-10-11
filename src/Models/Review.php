<?php

namespace Tourvisor\Models;


class Review extends AbstractModel
{
    protected $casts = [
        'rate' => 'integer'
    ];
}