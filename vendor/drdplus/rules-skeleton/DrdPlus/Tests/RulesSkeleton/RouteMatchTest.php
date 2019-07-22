<?php declare(strict_types=1);

namespace DrdPlus\Tests\RulesSkeleton;

use DrdPlus\RulesSkeleton\RouteMatch;
use Granam\String\StringTools;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Routing\Loader\YamlFileLoader;

class RouteMatchTest extends TestCase
{
    /**
     * @test
     * @expectedException \DrdPlus\RulesSkeleton\Exceptions\MissingRequiredPathInRouteMatch
     */
    public function I_can_not_create_it_without_path()
    {
        new RouteMatch([]);
    }

    /**
     * @test
     * @throws \ReflectionException
     */
    public function I_can_get_every_parameter_provided_by_match()
    {
        $yamlFileLoaderReflection = new \ReflectionClass(YamlFileLoader::class);
        $availableKeysReflection = $yamlFileLoaderReflection->getProperty('availableKeys');
        $availableKeysReflection->setAccessible(true);
        $availableKeys = $availableKeysReflection->getValue();
        $parameters = [];
        foreach ($availableKeys as $availableKey) {
            $parameters[$availableKey] = uniqid($availableKey, true);
        }
        $routeMatch = new RouteMatch($parameters);
        foreach ($availableKeys as $availableKey) {
            $getter = StringTools::assembleGetterForName($availableKey);
            self::assertSame($parameters[$availableKey], $routeMatch->$getter());
        }
    }

    /**
     * @test
     */
    public function Unknown_parameter_is_ignored()
    {
        $routeMatch = new RouteMatch(['path' => '/', 'your_very_origin' => 'Okavango']);
        self::assertSame('/', $routeMatch->getPath());
    }
}
