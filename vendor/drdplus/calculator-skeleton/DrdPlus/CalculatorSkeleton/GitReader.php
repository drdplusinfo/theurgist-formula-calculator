<?php
declare(strict_types = 1);

namespace DrdPlus\CalculatorSkeleton;

use Granam\Git\Git;

class GitReader extends Git
{

	public function cloneBranch(string $branch, string $repositoryUrl, string $destinationDir): array
	{
		throw new Exceptions\ForbiddenForGitReader(
			"Can not clone $branch from $repositoryUrl to $destinationDir by reader"
		);
	}

	public function updateBranch(string $branch, string $destinationDir): array
	{
		throw new Exceptions\ForbiddenForGitReader(
			"Can not update $branch in $destinationDir by reader"
		);
	}
}