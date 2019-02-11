<?php
declare(strict_types=1);

namespace DrdPlus\RulesSkeleton;

use Granam\Strict\Object\StrictObject;

class ContentIrrelevantParametersFilter extends StrictObject
{
    /** @var array|string[] */
    private $contentIrrelevantParameterNames;

    public function __construct(array $contentIrrelevantParameterNames)
    {
        $this->contentIrrelevantParameterNames = \array_fill_keys($contentIrrelevantParameterNames, '');
    }

    public function removeContentIrrelevantParameters(array $parameters): array
    {
        return \array_diff_key($parameters, $this->contentIrrelevantParameterNames);
    }
}