<?php
declare(strict_types=1);

namespace Granam\YamlReader;

class YamlFileReader extends YamlReader
{
    public function __construct(string $yamlFile)
    {
        parent::__construct($this->fetchContent($yamlFile));
    }

    /**
     * @param string $yamlFile
     * @return string
     * @throws \Granam\YamlReader\Exceptions\CanNotReadYamlFile
     */
    private function fetchContent(string $yamlFile): string
    {
        $yamlContent = \file_get_contents($yamlFile);
        if ($yamlContent === false) {
            throw new Exceptions\CanNotReadYamlFile("Can not parse content '{$yamlContent}' of YAML file '{$yamlFile}'");
        }

        return $yamlContent;
    }
}