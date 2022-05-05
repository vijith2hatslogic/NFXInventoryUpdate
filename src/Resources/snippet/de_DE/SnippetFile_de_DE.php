<?php

namespace NFX\InventoryUpdate\Resources\snippet\de_DE;


use Shopware\Core\System\Snippet\Files\SnippetFileInterface;

class SnippetFile_de_DE implements SnippetFileInterface
{
    public function getName(): string
    {
        return 'snippet.de-DE';
    }

    public function getPath(): string
    {
        return __DIR__ . '/snippet.de-DE.json';
    }

    public function getIso(): string
    {
        return 'de-DE';
    }

    public function getAuthor(): string
    {
        return 'NFX Media';
    }

    public function isBase(): bool
    {
        return false;
    }
}
