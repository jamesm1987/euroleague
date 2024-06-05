<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class League extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'api_id',
        'country_code',
        'active'
    ];

    protected $casts = [
        'active' => 'boolean',
    ];

    public function fixtures()
    {
        return $this->hasMany(Fixture::class);
    }

    public function teams()
    {
        return $this->hasMany(Team::class);
    }
}


