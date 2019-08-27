<?php declare(strict_types=1);

namespace Granam\Tests\ExceptionsHierarchy\Exceptions\ExceptionsUsedInParentDirs\PerfectSociety\PerfectFamily;

use Granam\Tests\ExceptionsHierarchy\Exceptions\ExceptionsUsedInParentDirs\PerfectSociety\PerfectFamily\Exceptions\Papa;

class PerfectMamaAtWork
{
    /**
     * @throws Papa
     */
    public function cleanHouse()
    {
        throw new Papa();
    }

    public static function getClass()
    {
        return __CLASS__;
    }
}