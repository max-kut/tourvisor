<?php

namespace Tourvisor\Models;


class SubRegion extends AbstractModel
{
    protected $casts = [
        'id'           => 'integer',
        'parentregion' => 'integer',
    ];
}