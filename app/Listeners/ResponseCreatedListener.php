<?php

namespace App\Listeners;

use App\Markdown\Mermaid\FencedCodeRenderer;
use App\Utilities\Minifier;
use Statamic\Events\ResponseCreated;

class ResponseCreatedListener
{
    public function handle(ResponseCreated $event)
    {
        if (count(FencedCodeRenderer::$mermaidRegions) == 0) {
            return;
        }

        $content = $event->response->getContent();

        $content = strtr($content, FencedCodeRenderer::$mermaidRegions);
        $content = (new Minifier)->minify($content);

        $event->response->setContent($content);

        FencedCodeRenderer::$mermaidRegions = [];
    }
}
