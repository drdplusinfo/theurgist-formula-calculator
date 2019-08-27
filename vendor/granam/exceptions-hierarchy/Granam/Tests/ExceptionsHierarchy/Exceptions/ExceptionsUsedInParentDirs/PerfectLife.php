<?php declare(strict_types=1);

namespace Granam\Tests\ExceptionsHierarchy\Exceptions\ExceptionsUsedInParentDirs;

use Granam\Tests\ExceptionsHierarchy\Exceptions\ExceptionsUsedInParentDirs\PerfectSociety\PerfectFamily\Exceptions\Mama;
use Granam\Tests\ExceptionsHierarchy\Exceptions\ExceptionsUsedInParentDirs\PerfectSociety\PerfectFamily\Exceptions\Papa;
use Granam\Tests\ExceptionsHierarchy\Exceptions\ExceptionsUsedInParentDirs\PerfectSociety\PerfectFamily\PerfectMamaAtWork;

class PerfectLife
{
    /**
     * @throws Mama
     */
    public function TakeGarbage()
    {
        try {
            $perfectMamaAtWork = new PerfectMamaAtWork();
            $perfectMamaAtWork->cleanHouse();
        } catch (Papa $papa) {
            throw new Mama();
        }
    }

    public static function getClass()
    {
        return __CLASS__;
    }
}