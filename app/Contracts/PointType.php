<?php

namespace App\Contracts;

interface PointType
{
    public function getDescription(): string;
    public function getValue(): int;
}