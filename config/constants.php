<?php

return [
    'API_ENDPOINTS' => [
        'LEAGUES'       => 'leagues',
        'SEASONS'       => 'seasons',
        'TEAMS'         => 'teams',
        'SQUAD'         => 'squad',
        'TEAM_RANKINGS' => 'team-rankings',
        'FIXTURES'      => 'fixtures',
        'STANDINGS'     => 'standings',
        'SEASON'        => 'season',
        'PLAYERS'       => 'players',
        'COUNTRIES'     => 'countries',
        'POSITIONS'     => 'positions',
        'VENUES'        => 'venues',
        'OFFICIALS'     => 'officials',
        'LIVESCORES'    => 'livescores',
        'STAGES'        => 'stages'
    ],
    'APPLICABLE_COUNTRIES'  => [
        'Australia', 'New Zealand', 'England', 'India', 'South Africa', 'Pakistan', 'Bangladesh', 'West Indies', 'Sri Lanka', 'Afghanistan', 'Netherlands', 'Ireland', 'Zimbabwe', 'Scotland', 'Oman', 'United Arab Emirates', 'Papua New Guinea'
    ],
    'MATCH_NOT_LIVE_STATUSES'    => [
        'Finished', 'Postp.', 'Int.', 'Aban.', 'Cancl.'
    ],
    'API_URL'       => 'https://cricket.sportmonks.com/api/v2.0/',
    'RAPID_API_NEWS'    => [
        'API_URL'   => 'https://t20-cricket-news.p.rapidapi.com/news',
        'HTTPHEADER'=> [
            "X-RapidAPI-Host: t20-cricket-news.p.rapidapi.com",
            "X-RapidAPI-Key: c103b1740cmsh8bbe280e1733466p15db25jsn351fcd36a803"
        ]
    ]
];