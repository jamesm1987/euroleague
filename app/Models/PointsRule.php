<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PointsRule extends Model
{
    use HasFactory;

    protected $fillable = [
        'description',
        'key',
        'outcome',
        'condition',
        'threshold',
        'value'
    ];

}
