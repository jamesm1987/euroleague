<?php

namespace App\Services;

use App\Models\Fixture;

interface FixturePoints
{
    public function apply(Fixture $fixture);
}