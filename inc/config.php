<?php
/**
 * Wakker Bier - Global Configuration
 */

// Environment & Base URL settings
$config = [
    'site_name'        => 'Wakker Bier',
    'site_tagline'     => 'Bierproeverijen',
    'sommelier_name'   => 'Sander Voorn',
    'sommelier_title'  => 'Gediplomeerd Internationaal Biersommelier',
    'phone'            => '+31616140742',
    'phone_display'    => '+(31) 6 1614 07 42',
    'phone_raw'        => '31616140742',
    'email'            => 'info@wakkerbier.nl',
    'address_street'   => 'Duikerstraat 38',
    'address_city'     => 'Aalsmeer',
    'address_zip'      => '1432JW',
    'address_country'  => 'NL',
    'base_url'         => 'https://wakkerbier.nl',
    'rating_value'     => '5.0',
    'review_count'     => '650',
    'year'             => date('Y') < 2026 ? 2026 : date('Y'),
];

// Helper to get active state for navigation
function is_active_page($page, $currentPage) {
    return ($page === $currentPage) ? ' active' : '';
}

// Format Euro prices nicely
function format_price($price) {
    return '€' . number_format($price, 2, ',', '.');
}

// Format Euro prices with "Vanaf" prefix
function format_price_vanaf($price) {
    return 'Vanaf ' . format_price($price);
}
