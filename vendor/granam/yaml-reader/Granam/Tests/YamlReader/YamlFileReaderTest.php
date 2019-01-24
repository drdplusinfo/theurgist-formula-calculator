<?php
declare(strict_types=1);

namespace Granam\Tests\YamlReader;

use Granam\YamlReader\YamlFileReader;
use PHPUnit\Framework\TestCase;

class YamlFileReaderTest extends TestCase
{
    /**
     * @test
     */
    public function I_can_get_values_from_yaml_file(): void
    {
        $yamlFile = $this->createYamlFile($data = ['foo' => 'bar', 'baz' => ['qux' => true]]);
        $yaml = new YamlFileReader($yamlFile);
        self::assertSame($data, $yaml->getValues());
        foreach ($data as $key => $value) {
            self::assertArrayHasKey($key, $yaml);
            self::assertSame($value, $yaml[$key]);
        }
    }

    private function createYamlFile(array $data): string
    {
        $file = sys_get_temp_dir() . '/' . uniqid('yaml_file_test', true);
        if (!yaml_emit_file($file, $data)) {
            throw new \RuntimeException('Can not write test data into ' . $file);
        }

        return $file;
    }

    /**
     * @test
     * @expectedException \Granam\YamlReader\Exceptions\YamlObjectContentIsReadOnly
     */
    public function I_can_not_set_value_on_yaml_object(): void
    {
        try {
            $yamlFile = $this->createYamlFile([]);
            $yaml = new YamlFileReader($yamlFile);
        } catch (\Exception $exception) {
            self::fail('No exception expected so far: ' . $exception->getMessage());
        }
        /** @noinspection OnlyWritesOnParameterInspection */
        $yaml['foo'] = 'bar';
    }

    /**
     * @test
     * @expectedException \Granam\YamlReader\Exceptions\YamlObjectContentIsReadOnly
     */
    public function I_can_not_remove_value_on_yaml_object(): void
    {
        try {
            $yamlFile = $this->createYamlFile(['foo' => 'bar']);
            $yaml = new YamlFileReader($yamlFile);
        } catch (\Exception $exception) {
            self::fail('No exception expected so far: ' . $exception->getMessage());
        }
        /** @noinspection PhpUndefinedVariableInspection */
        unset($yaml['foo']);
    }

}