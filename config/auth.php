<?php

return [

    'defaults' => [
        'guard' => 'academic_administrator', // Set default guard to academic_administrator
        'passwords' => 'academic_administrators', // Set default password reset provider
    ],

    'guards' => [
        'academic_administrator' => [
            'driver' => 'session',
            'provider' => 'academic_administrators',
        ],
        'research_staff' => [
            'driver' => 'session',
            'provider' => 'research_staff',
        ],
        'researcher' => [
            'driver' => 'session',
            'provider' => 'researchers',
        ],
        // Removed web guard to avoid using users table
    ],

    'providers' => [
        'academic_administrators' => [
            'driver' => 'eloquent',
            'model' => App\Models\AcademicAdministrator::class,
        ],
        'research_staff' => [
            'driver' => 'eloquent',
            'model' => App\Models\ResearchStaff::class,
        ],
        'researchers' => [
            'driver' => 'eloquent',
            'model' => App\Models\Researcher::class,
        ],
    ],

    // Updated password reset configuration
    'passwords' => [
        // Removed users password reset configuration
        'academic_administrators' => [
            'provider' => 'academic_administrators',
            'table' => 'password_reset_tokens', // Ensure this table exists or create it if necessary
            'expire' => 60,
            'throttle' => 60,
        ],
        'research_staff' => [
            'provider' => 'research_staff',
            'table' => 'password_reset_tokens',
            'expire' => 60,
            'throttle' => 60,
        ],
        'researchers' => [
            'provider' => 'researchers',
            'table' => 'password_reset_tokens',
            'expire' => 60,
            'throttle' => 60,
        ],
    ],

    // Password timeout setting
    'password_timeout' => 10800,

];
