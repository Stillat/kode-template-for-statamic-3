<?php

namespace App\Markdown\Mermaid;

use Illuminate\Support\Str;
use League\CommonMark\Extension\CommonMark\Node\Block\FencedCode;
use League\CommonMark\Extension\CommonMark\Renderer\Block\FencedCodeRenderer as CommonMarkRenderer;
use League\CommonMark\Node\Node;
use League\CommonMark\Renderer\ChildNodeRendererInterface;
use League\CommonMark\Renderer\NodeRendererInterface;
use League\CommonMark\Util\HtmlElement;
use League\CommonMark\Xml\XmlNodeRendererInterface;

class FencedCodeRenderer implements NodeRendererInterface, XmlNodeRendererInterface
{
    // The base renderer for fenced code blocks.
    protected $baseRenderer;

    // An associative array for storing Mermaid regions.
    public static $mermaidRegions = [];

    public function __construct()
    {
        $this->baseRenderer = new CommonMarkRenderer;
    }

    public function render(Node $node, ChildNodeRendererInterface $childRenderer)
    {
        FencedCode::assertInstanceOf($node);

        $attrs = $node->data->getData('attributes');

        $infoWords = $node->getInfoWords();

        // Check if the node has Mermaid syntax.
        if (count($infoWords) !== 0 && $infoWords[0] === 'mermaid') {
            $attrs->append('class', 'mermaid');

            // We will replace these later in
            // App\Listeners\ResponseCreatedListener to avoid
            // having to worry about HTML escaping.
            $regionName = 'mermaid_'.Str::uuid();
            self::$mermaidRegions[$regionName] = $node->getLiteral();

            return new HtmlElement(
                'div',
                $attrs->export(),
                $regionName);
        }

        // Default rendering if not Mermaid syntax.
        return $this->baseRenderer->render($node, $childRenderer);
    }

    public function getXmlTagName(Node $node): string
    {
        return $this->baseRenderer->getXmlTagName($node);
    }

    public function getXmlAttributes(Node $node): array
    {
        return $this->baseRenderer->getXmlAttributes($node);
    }
}
