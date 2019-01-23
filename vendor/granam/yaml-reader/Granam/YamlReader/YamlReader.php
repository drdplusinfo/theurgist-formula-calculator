<?php
declare(strict_types=1);

namespace Granam\YamlReader;

use Granam\Strict\Object\StrictObject;

class YamlReader extends StrictObject implements \ArrayAccess
{
    /** @var string */
    private $yamlContent;
    /** @var array */
    private $values;

    public function __construct(string $yamlContent)
    {
        $this->values = $this->parseValues($yamlContent);
    }

    /**
     * @param string $yamlContent
     * @return array
     * @throws \Granam\YamlReader\Exceptions\CanNotParseYamlFile
     */
    private function parseValues(string $yamlContent): array
    {
        if (!$yamlContent) {
            return [];
        }
        $values = \yaml_parse($yamlContent);
        if ($values !== false) {
            return $values;
        }
        throw new Exceptions\CanNotParseYamlFile("Can not parse content '{$yamlContent}' of YAML file '{$this->yamlContent}'");
    }

    public function getValues(): array
    {
        return $this->values;
    }

    public function offsetExists($offset): bool
    {
        return \array_key_exists($offset, $this->getValues());
    }

    public function offsetGet($offset)
    {
        return $this->getValues()[$offset] ?? null;
    }

    /**
     * @param mixed $offset
     * @param mixed $value
     * @throws \Granam\YamlReader\Exceptions\YamlObjectContentIsReadOnly
     */
    public function offsetSet($offset, $value): void
    {
        throw new Exceptions\YamlObjectContentIsReadOnly('Content of ' . static::class . ' can not be changed');
    }

    /**
     * @param mixed $offset
     * @throws \Granam\YamlReader\Exceptions\YamlObjectContentIsReadOnly
     */
    public function offsetUnset($offset): void
    {
        throw new Exceptions\YamlObjectContentIsReadOnly('Content of ' . static::class . ' can not be changed');
    }

}