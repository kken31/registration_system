<?php

return [
    'paths' => ['api/*'], // Apply CORS to your API routes
    'allowed_methods' => ['*'], // Or specify methods: ['GET', 'POST', 'PUT', 'DELETE']
    'allowed_origins' => ['http://localhost:8001'], // IMPORTANT: Change this to your frontend URL
    'allowed_origins_patterns' => [],
    'allowed_headers' => ['*'], // Or specify headers: ['Content-Type', 'Authorization']
    'exposed_headers' => [],
    'max_age' => 0,
    'supports_credentials' => false, // Set to true if you need to send cookies/sessions with requests
];
