<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Default Store SEO Information
    |--------------------------------------------------------------------------
    */
    'site_name' => 'ShopPulss',
    'default_title' => 'ShopPulss | Shop Quality Products Online in Pakistan',
    'title_separator' => '|',
    'default_description' => 'Shop products across popular categories at ShopPulss. Explore electronics, mobile phones, electrical items and more with clear product details and customer support in Pakistan.',
    'canonical_host' => env('APP_URL', 'https://shoppulss.com'),
    'default_locale' => 'en_PK',
    'default_og_type' => 'website',
    'default_image' => '/images/shoppulss-logo.png',
    'twitter_handle' => null,

    /*
    |--------------------------------------------------------------------------
    | Query Parameters to Strip from Canonical URLs
    |--------------------------------------------------------------------------
    */
    'strip_query_params' => [
        'utm_source',
        'utm_medium',
        'utm_campaign',
        'utm_term',
        'utm_content',
        'gclid',
        'fbclid',
        'msclkid',
        'ttclid',
        'ref',
        'source',
        'affiliate',
        '_ga',
        '_gl',
    ],

    /*
    |--------------------------------------------------------------------------
    | Disallowed Robots Paths
    |--------------------------------------------------------------------------
    */
    'robots_disallow' => [
        '/admin/',
        '/cart',
        '/cart/',
        '/checkout',
        '/checkout/',
        '/account/',
        '/search',
        '/api/',
        '/login',
        '/register',
        '/password/',
    ],
];
