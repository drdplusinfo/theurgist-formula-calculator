<?php
namespace Granam\WebContentBuilder\Web;

interface SourceDirProviderInterface
{
    public function getSourceDir(): string;
}