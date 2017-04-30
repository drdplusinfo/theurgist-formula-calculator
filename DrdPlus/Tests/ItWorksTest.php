<?php
namespace DrdPlus\Tests;

use PHPUnit\Framework\TestCase;

class ItWorksTest extends TestCase
{
    protected function setUp()
    {
        $this->setBackupGlobals(true);
    }

    /**
     * @test
     */
    public function I_can_load_it_without_error()
    {
        $_SERVER['QUERY_STRING'] = '';
        ob_start();
        require __DIR__ . '/../../index.php';
        $content = ob_get_clean();
        self::assertRegExp('~^<!DOCTYPE html>\n.+</html>$~s', $content);
    }
}