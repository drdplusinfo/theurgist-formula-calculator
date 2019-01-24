<?php
declare(strict_types = 1);

namespace DrdPlus\Tests\CalculatorSkeleton;

use DrdPlus\CalculatorSkeleton\GitReader;
use Granam\Tests\Tools\TestWithMockery;

class GitReaderTest extends TestWithMockery
{

	/**
	 * @test
	 * @expectedException \DrdPlus\CalculatorSkeleton\Exceptions\ForbiddenForGitReader
	 */
	public function I_can_not_clone_repository_by_reader(): void
	{
		$gitReader = new GitReader();
		$gitReader->cloneBranch('foo', 'bar', 'baz');
	}

	/**
	 * @test
	 * @expectedException \DrdPlus\CalculatorSkeleton\Exceptions\ForbiddenForGitReader
	 */
	public function I_can_not_update_repository_by_reader(): void
	{
		$gitReader = new GitReader();
		$gitReader->updateBranch('foo', 'bar');
	}
}