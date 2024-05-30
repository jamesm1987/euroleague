<?php

namespace App\Traits;


trait ExtractsArrayData
{
    protected function extract(array $array, string $key): array
    {
        if (isset($array[$key])) {
            return [$key, $array[$key]];
        }

    }
}