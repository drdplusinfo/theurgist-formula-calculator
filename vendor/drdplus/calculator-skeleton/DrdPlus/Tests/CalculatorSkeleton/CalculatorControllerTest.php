<?php
declare(strict_types=1);

namespace DrdPlus\Tests\CalculatorSkeleton;

use DrdPlus\CalculatorSkeleton\CalculatorContent;
use DrdPlus\CalculatorSkeleton\CalculatorApplication;
use DrdPlus\CalculatorSkeleton\CalculatorServicesContainer;
use DrdPlus\RulesSkeleton\Configuration;
use DrdPlus\RulesSkeleton\HtmlHelper;
use DrdPlus\Tests\RulesSkeleton\Partials\AbstractContentTest;
use Mockery\MockInterface;

/**
 * @method CalculatorServicesContainer createServicesContainer(Configuration $configuration = null, HtmlHelper $htmlHelper = null)
 */
class CalculatorControllerTest extends AbstractContentTest
{
    use Partials\AbstractContentTestTrait;

    /**
     * @test
     * @backupGlobals enabled
     */
    public function I_can_get_original_as_well_as_modified_url(): void
    {
        $_SERVER['REQUEST_URI'] = 'http://odpocinek.drdplus.loc/?remember_current=1&strength=0&will=0&race=human&sub_race=common&gender=male&roll_against_malus_from_wounds=9&fresh_wound_size[]=1&serious_wound_origin[]=mechanical_stab';
        $_GET = [
            'remember_current' => '1',
            'strength' => '0',
            'will' => '0',
            'race' => 'human',
            'sub_race' => 'common',
            'gender' => 'male',
            'roll_against_malus_from_wounds' => '9',
            'fresh_wound_size' => ['1'],
            'serious_wound_origin' => ['mechanical_stab'],
        ];
        $controller = new CalculatorApplication($this->createServicesContainer());
        self::assertSame($_SERVER['REQUEST_URI'], $controller->getRequestUrl());
        self::assertSame(
            \str_replace(['remember_current=1', '[]'], ['remember_current=0', \urlencode('[]')], $_SERVER['REQUEST_URI']),
            $controller->getRequestUrl(['remember_current' => '0'])
        );
    }

    /**
     * @param CalculatorContent $calculatorContent
     * @return CalculatorServicesContainer|MockInterface
     */
    protected function createServicesContainerWithContent(CalculatorContent $calculatorContent): CalculatorServicesContainer
    {
        $servicesContainer = $this->mockery(CalculatorServicesContainer::class);
        $servicesContainer->shouldReceive('getConfiguration')
            ->andReturn($this->getConfiguration());
        $servicesContainer->shouldReceive('getCalculatorContent')
            ->andReturn($calculatorContent);

        return $servicesContainer;
    }
}