<?php

namespace App\Providers;

use App\Markdown\CustomExtension;
use Illuminate\Support\ServiceProvider;
use League\CommonMark\Extension\HeadingPermalink\HeadingPermalinkExtension;
use Statamic\Facades\Markdown;
use Statamic\Markdown\Parser;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Markdown::extend('default', function (Parser $parser) {
            $parser = $parser->newInstance([
                'heading_permalink' => [
                    'html_class' => 'heading-permalink',
                    'id_prefix' => 'content',
                    'fragment_prefix' => 'content',
                    'insert' => 'before',
                    'min_heading_level' => 2,
                    'max_heading_level' => 6,
                    'title' => 'Permalink',
                    'symbol' => '#',
                    'aria_hidden' => true,
                ],
            ]);

            $parser->addExtensions(function () {
                return [
                    new CustomExtension,
                    new HeadingPermalinkExtension,
                ];
            });

            return $parser;
        });
    }
}
