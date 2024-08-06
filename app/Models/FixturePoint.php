<?php

namespace App\Models;

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FixturePoint extends Model
{
    
    use HasFactory;

    protected $fillable = [];

    protected $table = 'fixture_point';

    public function pointsRule()
    {
        return $this->belongsTo(PointsRule::class);
    }

}
