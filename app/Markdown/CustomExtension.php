<?php

namespace App\Markdown;

use App\Markdown\Mermaid\FencedCodeRenderer;
use League\CommonMark\Environment\EnvironmentBuilderInterface;
use League\CommonMark\Extension\CommonMark\Node\Block\FencedCode;
use League\CommonMark\Extension\ExtensionInterface;
use League\CommonMark\Extension\GithubFlavoredMarkdownExtension;

class CustomExtension implements ExtensionInterface
{
    public function register(EnvironmentBuilderInterface $environment): void
    {
        $environment->addExtension(new GithubFlavoredMarkdownExtension);
        $environment->addRenderer(FencedCode::class, new FencedCodeRenderer, 10);
    }
}
