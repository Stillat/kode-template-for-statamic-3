<?php

use App\Utilities\SiteMapGenerator;
use Illuminate\Support\Facades\Route;

Route::get('robots.txt', function () {
    $robots = <<<'ROBOTS'
User-agent: *
Disallow:

Sitemap: 
ROBOTS;

    $robots .= route('sitemap.xml');

    return response($robots)
        ->header('Content-Type', 'text/plain');
});

Route::get('sitemap.xml', function () {
    $generator = new SiteMapGenerator;

    return response($generator->generate())
        ->header('Content-Type', 'text/xml');
})->name('sitemap.xml');
