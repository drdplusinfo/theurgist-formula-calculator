# Git stupid reader

For more sophisticated Git workflow, try for example [sebastianfeldmann/git](https://packagist.org/packages/sebastianfeldmann/git).

Current package can just
- get Git status
- get diff against origin master
- get last commit hash
- get patch versions from tags
- get all version-like branches
- clone a branch
- update a branch
- say if a remote branch exists

## Usage

```php
<?php
$git = new \Granam\Git\Git();
$patchVersions = $git->getTagPatchVersions(__DIR__);
print_r($patchVersions); // [1.0.0]
$minorVersions = $git->getAllMinorVersionLikeBranches(__DIR__);
print_r($minorVersions); // [1.0]