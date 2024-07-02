<?php

namespace App\Models;

use App\Contracts\PointType;

class FixturePoints implements PointType
{
    
    protected $value;

    public function __construct(int $value)
    {
        $this->value = $value;
    }

    public function getDescription(): string
    {
        return 'fixture_points';
    }


}
