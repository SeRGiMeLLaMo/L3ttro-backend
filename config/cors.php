<?php

return [

    'paths' => ['api/*', 'sanctum/csrf-cookie'],

    'allowed_methods' => ['*'],

    'allowed_origins' => [
        'http://localhost:5173',
        'https://lettro-frontend.vercel.app',
        'https://lettro-frontend-95kcx6vst-sergimellamos-projects.vercel.app',
        'https://lettro-frontend-mmamofwxv-sergimellamos-projects.vercel.app',
    ],

    'allowed_origins_patterns' => [],

    'allowed_headers' => ['*'],

    'exposed_headers' => [],

    'max_age' => 0,

    'supports_credentials' => true,

];
