<?php

return [
    'result' => [
        'win' => 3,
        'draw' => 1,
    ],
    'score' => [
        'threshold' => 3,
        'home' => [
            'win' => 1,
            'defeat' => -2,
        ],
        'away' => [
            'win' => 2,
            'defeat' => -1
        ]
    ],
    'goalscorer' => [
        '1' => 20,
        '2' => 15,
        '3' => 10,
        '4' => 5

    ],
    'trophy' => [
        'champions-league' => 40,
        'league' => 20,
        'europa-league' => 15,
        'conference-league' => 15,
        'domestic-cup' => 10
    ]
];