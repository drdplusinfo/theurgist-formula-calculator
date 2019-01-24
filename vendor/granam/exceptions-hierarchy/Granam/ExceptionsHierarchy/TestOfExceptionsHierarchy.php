<?php
namespace Granam\ExceptionsHierarchy;

class TestOfExceptionsHierarchy
{
    /** @var string */
    private $testedNamespace;

    /** @var string */
    private $rootNamespace;

    /** @var string */
    private $exceptionsSubDir;

    /** @var array|\string[] */
    private $externalRootNamespaces = [];

    /**
     * @param string $testedNamespace
     * @param string $rootNamespace
     * @param string|bool $exceptionsSubDir
     * @param array|string[] $externalRootNamespaces
     * @param string|bool $externalRootExceptionsSubDir
     * @throws \Granam\ExceptionsHierarchy\Exceptions\MissingNamespace
     * @throws \Granam\ExceptionsHierarchy\Exceptions\RootNamespaceHasToBeSuperior
     * @throws \Granam\ExceptionsHierarchy\Exceptions\ExternalRootNamespaceHasToBeSuperior
     * @throws \Granam\ExceptionsHierarchy\Exceptions\TagInterfaceNotFound
     * @throws \Granam\ExceptionsHierarchy\Exceptions\InvalidTagInterfaceHierarchy
     * @throws \Granam\ExceptionsHierarchy\Exceptions\InvalidExceptionHierarchy
     */
    public function __construct(
        $testedNamespace,
        $rootNamespace,
        $exceptionsSubDir = 'Exceptions',
        array $externalRootNamespaces = [],
        $externalRootExceptionsSubDir = 'Exceptions'
    )
    {
        $testedNamespace = $this->normalizeNamespace($testedNamespace);
        $rootNamespace = $this->normalizeNamespace($rootNamespace);
        $externalRootNamespaces = $this->normalizeNamespaces($externalRootNamespaces);
        $this->checkRootNamespace($rootNamespace, $testedNamespace);
        $this->checkExternalRootNamespaces($externalRootNamespaces, $externalRootExceptionsSubDir, $rootNamespace);

        $this->testedNamespace = $testedNamespace;
        $this->rootNamespace = $rootNamespace;
        $this->exceptionsSubDir = $this->normalizeSubDir($exceptionsSubDir);
        $this->externalRootNamespaces = $externalRootNamespaces;
        $this->externalRootExceptionsSubDir = $externalRootExceptionsSubDir;
    }

    /**
     * @param string $namespace
     * @return string
     * @throws \Granam\ExceptionsHierarchy\Exceptions\MissingNamespace
     */
    protected function normalizeNamespace($namespace)
    {
        if (!is_string($namespace)) {
            throw new Exceptions\MissingNamespace(
                'Namespace can be empty string for root, but given ' . var_export($namespace, true)
            );
        }

        return '\\' . trim($namespace, '\\');
    }

    /**
     * @param array|string[] $namespaces
     * @return array
     * @throws \Granam\ExceptionsHierarchy\Exceptions\MissingNamespace
     */
    protected function normalizeNamespaces(array $namespaces)
    {
        foreach ($namespaces as &$namespace) {
            $namespace = $this->normalizeNamespace($namespace);
        }

        return $namespaces;
    }

    /**
     * @param string $rootNamespace
     * @param string $testedNamespace
     * @throws \Granam\ExceptionsHierarchy\Exceptions\RootNamespaceHasToBeSuperior
     */
    protected function checkRootNamespace($rootNamespace, $testedNamespace)
    {
        if (!preg_match('~^' . preg_quote($rootNamespace, '~') . '~', $testedNamespace)) {
            throw new Exceptions\RootNamespaceHasToBeSuperior(
                "Root namespace $rootNamespace should be leading of currently tested namespace $testedNamespace"
            );
        }
    }

    /**
     * @param array $externalRootNamespaces
     * @param string $externalRootExceptionsSubDir
     * @param string $rootNamespace
     * @throws \Granam\ExceptionsHierarchy\Exceptions\ExternalRootNamespaceHasToBeSuperior
     * @throws \Granam\ExceptionsHierarchy\Exceptions\TagInterfaceNotFound
     * @throws \Granam\ExceptionsHierarchy\Exceptions\InvalidTagInterfaceHierarchy
     * @throws \Granam\ExceptionsHierarchy\Exceptions\InvalidExceptionHierarchy
     * @throws \Granam\ExceptionsHierarchy\Exceptions\MissingNamespace
     */
    protected function checkExternalRootNamespaces(array $externalRootNamespaces, $externalRootExceptionsSubDir, $rootNamespace)
    {
        if (!$externalRootNamespaces) {
            return;
        }
        foreach ($externalRootNamespaces as $externalRootNamespace) {
            if ($rootNamespace === $externalRootNamespace) {
                throw new Exceptions\ExternalRootNamespaceHasToBeSuperior(
                    "External root namespace $externalRootNamespace should differ to local root namespace $rootNamespace"
                );
            }
            if (strpos($externalRootNamespace, $rootNamespace) === 0) {
                throw new Exceptions\ExternalRootNamespaceHasToBeSuperior(
                    "External root namespace $externalRootNamespace should not be subordinate to local root namespace $rootNamespace"
                );
            }
            $this->My_tag_interfaces_are_in_hierarchy(
                $externalRootNamespace,
                $externalRootExceptionsSubDir,
                [] // no child namespaces to check
            );
        }
    }

    /**
     * @param string $dir
     * @return string
     */
    protected function normalizeDir($dir)
    {
        $normalizedSlash = str_replace(['\\', '/'], DIRECTORY_SEPARATOR, $dir);

        return rtrim($normalizedSlash, DIRECTORY_SEPARATOR);
    }

    /**
     * @param string $subDir
     * @return string
     */
    protected function normalizeSubDir($subDir)
    {
        return ltrim($this->normalizeDir($subDir), DIRECTORY_SEPARATOR);
    }

    /**
     * @return string
     */
    protected function getTestedNamespace()
    {
        return $this->testedNamespace;
    }

    /**
     * @return string
     */
    protected function getRootNamespace()
    {
        return $this->rootNamespace;
    }

    /**
     * @return string
     */
    protected function getExceptionsSubDir()
    {
        return $this->exceptionsSubDir;
    }

    /**
     * @return array|string[]
     */
    protected function getExternalRootNamespaces()
    {
        return $this->externalRootNamespaces;
    }

    /**
     * @return bool|string
     */
    protected function getExternalRootExceptionsSubDir()
    {
        return $this->externalRootExceptionsSubDir;
    }

    /**
     * @param string $testedNamespace
     * @param string $exceptionsSubDir
     * @param array $childNamespaces
     * @throws \Granam\ExceptionsHierarchy\Exceptions\TagInterfaceNotFound
     * @throws \Granam\ExceptionsHierarchy\Exceptions\InvalidTagInterfaceHierarchy
     * @throws \Granam\ExceptionsHierarchy\Exceptions\InvalidExceptionHierarchy
     * @throws \Granam\ExceptionsHierarchy\Exceptions\MissingNamespace
     */
    protected function My_tag_interfaces_are_in_hierarchy($testedNamespace, $exceptionsSubDir, array $childNamespaces)
    {
        $exceptionInterface = $this->assembleExceptionInterfaceClass($testedNamespace, $exceptionsSubDir);
        $this->checkExceptionInterfaces($exceptionInterface);

        $runtimeInterface = $this->assembleRuntimeInterfaceClass($testedNamespace, $exceptionsSubDir);
        $this->checkRuntimeInterfaces($runtimeInterface, $exceptionInterface);

        $logicInterface = $this->assembleLogicInterfaceClass($testedNamespace, $exceptionsSubDir);
        $this->checkLogicInterfaces($logicInterface, $exceptionInterface);

        $this->checkInterfaceCollision($runtimeInterface, $logicInterface);

        $this->checkChildInterfaces($childNamespaces, $exceptionInterface, $runtimeInterface, $logicInterface);
    }

    /**
     * @param string $exceptionInterface
     * @throws \Granam\ExceptionsHierarchy\Exceptions\TagInterfaceNotFound
     * @throws \Granam\ExceptionsHierarchy\Exceptions\InvalidTagInterfaceHierarchy
     * @throws \Granam\ExceptionsHierarchy\Exceptions\MissingNamespace
     */
    private function checkExceptionInterfaces($exceptionInterface)
    {
        $externalRootExceptionInterfaces = $this->getExternalRootExceptionInterfaceClasses();
        if ($externalRootExceptionInterfaces) {
            foreach ($externalRootExceptionInterfaces as $externalRootExceptionInterface) {
                $this->checkExceptionInterface($exceptionInterface, $externalRootExceptionInterface);
            }
        } else {
            $this->checkExceptionInterface($exceptionInterface, false);
        }
    }

    /**
     * @param string $runtimeInterface
     * @param string $exceptionInterface
     * @throws \Granam\ExceptionsHierarchy\Exceptions\TagInterfaceNotFound
     * @throws \Granam\ExceptionsHierarchy\Exceptions\InvalidTagInterfaceHierarchy
     * @throws \Granam\ExceptionsHierarchy\Exceptions\MissingNamespace
     */
    private function checkRuntimeInterfaces($runtimeInterface, $exceptionInterface)
    {
        $externalRootRuntimeInterfaces = $this->getExternalRootRuntimeInterfaceClasses();
        if ($externalRootRuntimeInterfaces) {
            foreach ($externalRootRuntimeInterfaces as $externalRootRuntimeInterface) {
                $this->checkRuntimeInterface(
                    $runtimeInterface,
                    $exceptionInterface,
                    $externalRootRuntimeInterface
                );
            }
        } else {
            $this->checkRuntimeInterface($runtimeInterface, $exceptionInterface, false);
        }
    }

    /**
     * @param string $logicInterface
     * @param string $exceptionInterface
     * @throws \Granam\ExceptionsHierarchy\Exceptions\TagInterfaceNotFound
     * @throws \Granam\ExceptionsHierarchy\Exceptions\InvalidTagInterfaceHierarchy
     * @throws \Granam\ExceptionsHierarchy\Exceptions\MissingNamespace
     */
    private function checkLogicInterfaces($logicInterface, $exceptionInterface)
    {
        $externalRootLogicInterfaces = $this->getExternalRootLogicInterfaceClasses();
        if ($externalRootLogicInterfaces) {
            foreach ($externalRootLogicInterfaces as $externalRootLogicInterface) {
                $this->checkLogicInterface($logicInterface, $exceptionInterface, $externalRootLogicInterface);
            }
        } else {
            $this->checkLogicInterface($logicInterface, $exceptionInterface, false);
        }
    }

    /**
     * @return array|string[]
     * @throws \Granam\ExceptionsHierarchy\Exceptions\MissingNamespace
     */
    private function getExternalRootExceptionInterfaceClasses()
    {
        $classes = [];
        foreach ($this->getExternalRootNamespaces() as $externalRootNamespace) {
            $classes[] = $this->assembleExceptionInterfaceClass($externalRootNamespace, $this->getExternalRootExceptionsSubDir());
        }

        return $classes;
    }

    /**
     * @return array|string[]
     * @throws \Granam\ExceptionsHierarchy\Exceptions\MissingNamespace
     */
    private function getExternalRootRuntimeInterfaceClasses()
    {
        $classes = [];
        foreach ($this->getExternalRootNamespaces() as $externalRootNamespace) {
            $classes[] = $this->assembleRuntimeInterfaceClass($externalRootNamespace, $this->getExternalRootExceptionsSubDir());
        }

        return $classes;
    }

    /**
     * @return array|string[]
     * @throws \Granam\ExceptionsHierarchy\Exceptions\MissingNamespace
     */
    private function getExternalRootLogicInterfaceClasses()
    {
        $classes = [];
        foreach ($this->getExternalRootNamespaces() as $externalRootNamespace) {
            $classes[] = $this->assembleLogicInterfaceClass($externalRootNamespace, $this->getExternalRootExceptionsSubDir());
        }

        return $classes;
    }

    /**
     * @param $exceptionInterface
     * @param string|bool $externalRootExceptionInterface
     * @throws \Granam\ExceptionsHierarchy\Exceptions\TagInterfaceNotFound
     * @throws \Granam\ExceptionsHierarchy\Exceptions\InvalidTagInterfaceHierarchy
     */
    private function checkExceptionInterface($exceptionInterface, $externalRootExceptionInterface)
    {
        if (!interface_exists($exceptionInterface)) {
            throw new Exceptions\TagInterfaceNotFound("Tag interface $exceptionInterface not found");
        }
        if ($externalRootExceptionInterface && !is_a($exceptionInterface, $externalRootExceptionInterface, true)) {
            throw new Exceptions\InvalidTagInterfaceHierarchy(
                "Tag interface $exceptionInterface should extends external parent tag interface $externalRootExceptionInterface"
            );
        }
    }

    /**
     * @param string $runtimeInterface
     * @param string $exceptionInterface
     * @param string $externalRootRuntimeInterface
     * @throws \Granam\ExceptionsHierarchy\Exceptions\TagInterfaceNotFound
     * @throws \Granam\ExceptionsHierarchy\Exceptions\InvalidTagInterfaceHierarchy
     */
    private function checkRuntimeInterface($runtimeInterface, $exceptionInterface, $externalRootRuntimeInterface)
    {
        if (!interface_exists($runtimeInterface)) {
            throw new Exceptions\TagInterfaceNotFound("Runtime tag interface $runtimeInterface not found");
        }
        if (!is_a($runtimeInterface, $exceptionInterface, true)) {
            throw new Exceptions\InvalidTagInterfaceHierarchy(
                "Runtime tag interface $runtimeInterface is not a child of $exceptionInterface"
            );
        }
        if ($externalRootRuntimeInterface && !is_a($runtimeInterface, $externalRootRuntimeInterface, true)) {
            throw new Exceptions\InvalidTagInterfaceHierarchy(
                "Tag interface $runtimeInterface should extends external parent tag interface $externalRootRuntimeInterface"
            );
        }
    }

    /**
     * @param string $logicInterface
     * @param string $exceptionInterface
     * @param string $externalRootLogicInterface
     * @throws \Granam\ExceptionsHierarchy\Exceptions\TagInterfaceNotFound
     * @throws \Granam\ExceptionsHierarchy\Exceptions\InvalidTagInterfaceHierarchy
     */
    private function checkLogicInterface($logicInterface, $exceptionInterface, $externalRootLogicInterface)
    {
        if (!interface_exists($logicInterface)) {
            throw new Exceptions\TagInterfaceNotFound("Logic tag interface $logicInterface not found");
        }
        if (!is_a($logicInterface, $exceptionInterface, true)) {
            throw new Exceptions\InvalidTagInterfaceHierarchy(
                "Logic tag interface $logicInterface is not a child of $exceptionInterface"
            );
        }
        if ($externalRootLogicInterface && !is_a($logicInterface, $externalRootLogicInterface, true)) {
            throw new Exceptions\InvalidTagInterfaceHierarchy(
                "Tag interface $logicInterface should extends external parent tag interface $externalRootLogicInterface"
            );
        }
    }

    /**
     * @param string $runtimeInterface
     * @param string $logicInterface
     * @throws \Granam\ExceptionsHierarchy\Exceptions\InvalidTagInterfaceHierarchy
     */
    private function checkInterfaceCollision($runtimeInterface, $logicInterface)
    {
        if (is_a($runtimeInterface, $logicInterface, true)) {
            throw new Exceptions\InvalidTagInterfaceHierarchy(
                "Runtime tag interface $runtimeInterface can not be a logic tag"
            );
        }
        if (is_a($logicInterface, $runtimeInterface, true)) {
            throw new Exceptions\InvalidTagInterfaceHierarchy(
                "Logic tag interface $logicInterface can not be a runtime tag"
            );
        }
    }

    /**
     * @param array|string[] $childNamespaces
     * @param string $exceptionInterface
     * @param string $runtimeInterface
     * @param string $logicInterface
     * @throws \Granam\ExceptionsHierarchy\Exceptions\InvalidExceptionHierarchy
     * @throws \Granam\ExceptionsHierarchy\Exceptions\MissingNamespace
     */
    private function checkChildInterfaces(array $childNamespaces, $exceptionInterface, $runtimeInterface, $logicInterface)
    {
        foreach ($childNamespaces as $childNamespace) {
            $childExceptionInterface = $this->assembleExceptionInterfaceClass($childNamespace, $this->getExceptionsSubDir());
            if (!is_a($childExceptionInterface, $exceptionInterface, true)) {
                throw new Exceptions\InvalidExceptionHierarchy(
                    "Tag $childExceptionInterface should be child of $exceptionInterface"
                );
            }

            $childRuntimeInterface = $this->assembleRuntimeInterfaceClass($childNamespace, $this->getExceptionsSubDir());
            if (!is_a($childRuntimeInterface, $runtimeInterface, true)) {
                throw new Exceptions\InvalidExceptionHierarchy(
                    "Tag $childRuntimeInterface should be child of $runtimeInterface"
                );
            }

            $childLogicInterface = $this->assembleLogicInterfaceClass($childNamespace, $this->getExceptionsSubDir());
            if (!is_a($childLogicInterface, $logicInterface, true)) {
                throw new Exceptions\InvalidExceptionHierarchy(
                    "Tag $childLogicInterface should be child of $logicInterface"
                );
            }
        }
    }

    /**
     * @return bool
     * @throws \Granam\ExceptionsHierarchy\Exceptions\ExceptionClassNotFoundByAutoloader
     * @throws \Granam\ExceptionsHierarchy\Exceptions\ExceptionIsNotTaggedProperly
     * @throws \Granam\ExceptionsHierarchy\Exceptions\InvalidExceptionHierarchy
     * @throws \Granam\ExceptionsHierarchy\Exceptions\TagInterfaceNotFound
     * @throws \Granam\ExceptionsHierarchy\Exceptions\InvalidTagInterfaceHierarchy
     * @throws \Granam\ExceptionsHierarchy\Exceptions\MissingNamespace
     */
    public function My_exceptions_are_in_family_tree()
    {
        $childNamespaces = [];
        $testedNamespace = $this->getTestedNamespace();
        do {
            $this->My_tag_interfaces_are_in_hierarchy($testedNamespace, $this->getExceptionsSubDir(), $childNamespaces);
            $directory = $this->getNamespaceDirectory($testedNamespace);
            foreach ($this->getCustomExceptionsFrom($directory) as $customExceptionClass) {
                $this->My_exception_exists($customExceptionClass);
                $this->My_exception_is_properly_tagged($customExceptionClass);
                $this->My_custom_exception_follows_parent($customExceptionClass);
            }
            $alreadyInRoot = $testedNamespace === $this->getRootNamespace();
            $childNamespaces[] = $testedNamespace;
            $testedNamespace = $this->extractParentNamespace($testedNamespace, $this->getExceptionsSubDir());
        } while (!$alreadyInRoot && $testedNamespace);

        return true;
    }

    /**
     * @param string $namespace
     * @return string
     * @throws \Granam\ExceptionsHierarchy\Exceptions\MissingNamespace
     */
    protected function getNamespaceDirectory($namespace)
    {
        $exceptionTag = $this->assembleExceptionInterfaceClass($namespace, $this->getExceptionsSubDir());
        $exceptionTagReflection = new \ReflectionClass($exceptionTag);
        $filename = $exceptionTagReflection->getFileName();

        return dirname($filename);
    }

    /**
     * @param string $directory
     * @return array|string[]
     */
    protected function getCustomExceptionsFrom($directory)
    {
        $customExceptions = [];
        foreach (scandir($directory) as $file) {
            $filePath = $directory . DIRECTORY_SEPARATOR . $file;
            if (is_file($filePath)) {
                $content = file_get_contents($filePath);
                if (preg_match('~(namespace\s+(?<namespace>(\w+(\\\)?)+)).+(class|interface)\s+(?<className>\w+)~s', $content, $matches)
                    && !in_array($matches['className'], ['Exception', 'Runtime', 'Logic'], true)
                ) {
                    $customExceptions[] = $matches['namespace'] . '\\' . $matches['className'];
                }
            }
        }

        return $customExceptions;
    }

    /**
     * @param string $exceptionClass
     * @throws \Granam\ExceptionsHierarchy\Exceptions\ExceptionClassNotFoundByAutoloader
     */
    protected function My_exception_exists($exceptionClass)
    {
        if (!class_exists($exceptionClass) && !interface_exists($exceptionClass)) {
            throw new Exceptions\ExceptionClassNotFoundByAutoloader(
                "Exception class nor interface {$exceptionClass} has not been found by auto-loader."
                . ' Do you follow auto-loader expectations like PSR naming standards?'
            );
        }
    }

    /**
     * @param string $exceptionClass
     * @throws \Granam\ExceptionsHierarchy\Exceptions\ExceptionIsNotTaggedProperly
     * @throws \Granam\ExceptionsHierarchy\Exceptions\InvalidExceptionHierarchy
     * @throws \Granam\ExceptionsHierarchy\Exceptions\MissingNamespace
     */
    protected function My_exception_is_properly_tagged($exceptionClass)
    {
        $namespace = $this->extractNamespaceFromClass($exceptionClass);
        $this->checkIfIsBaseTagged($exceptionClass, $namespace);
        $this->checkTagCollision($exceptionClass, $namespace);

        if (class_exists($exceptionClass)) {
            $this->My_exception_is_child_of_proper_base_exception($exceptionClass);
        }
    }

    /**
     * @param string $exceptionClass
     * @param string $namespace
     * @throws \Granam\ExceptionsHierarchy\Exceptions\ExceptionIsNotTaggedProperly
     * @throws \Granam\ExceptionsHierarchy\Exceptions\MissingNamespace
     */
    private function checkIfIsBaseTagged($exceptionClass, $namespace)
    {
        $isBaseTagged = is_a($exceptionClass, $this->assembleExceptionInterfaceClass($namespace), true);
        if (!$isBaseTagged) {
            throw new Exceptions\ExceptionIsNotTaggedProperly(
                (class_exists($exceptionClass) ? 'Class' : 'Tag interface')
                . " $exceptionClass has to be tagged by Exception interface"
            );
        }
    }

    /**
     * @param string $exceptionClass
     * @param string $namespace
     * @throws \Granam\ExceptionsHierarchy\Exceptions\ExceptionIsNotTaggedProperly
     * @throws \Granam\ExceptionsHierarchy\Exceptions\MissingNamespace
     */
    private function checkTagCollision($exceptionClass, $namespace)
    {
        $isRuntime = $this->isRuntime($exceptionClass, $namespace);
        $isLogic = $this->isLogic($exceptionClass, $namespace);
        if ($isRuntime && $isLogic) {
            throw new Exceptions\ExceptionIsNotTaggedProperly(
                'Exception ' . (class_exists($exceptionClass) ? 'class' : 'interface')
                . " $exceptionClass can not be tagged by Runtime interface and Logic interface at the same time"
            );
        }
        if (!$isRuntime && !$isLogic) {
            throw new Exceptions\ExceptionIsNotTaggedProperly(
                'Exception ' . (class_exists($exceptionClass) ? 'class' : 'interface')
                . " $exceptionClass is not tagged by Runtime interface or even Logic interface"
            );
        }
    }

    /**
     * @param string $exceptionClass
     * @param string $namespace
     * @return bool
     * @throws \Granam\ExceptionsHierarchy\Exceptions\MissingNamespace
     */
    private function isRuntime($exceptionClass, $namespace)
    {
        return is_a($exceptionClass, $this->assembleRuntimeInterfaceClass($namespace), true);
    }

    /**
     * @param string $exceptionClass
     * @param string $namespace
     * @return bool
     * @throws \Granam\ExceptionsHierarchy\Exceptions\MissingNamespace
     */
    private function isLogic($exceptionClass, $namespace)
    {
        return is_a($exceptionClass, $this->assembleLogicInterfaceClass($namespace), true);
    }

    /**
     * @param string $exceptionClass
     * @throws \Granam\ExceptionsHierarchy\Exceptions\InvalidExceptionHierarchy
     * @throws \Granam\ExceptionsHierarchy\Exceptions\MissingNamespace
     */
    protected function My_exception_is_child_of_proper_base_exception($exceptionClass)
    {
        if (!is_a($exceptionClass, '\Exception', true)) {
            throw new Exceptions\InvalidExceptionHierarchy("$exceptionClass should be child of \\Exception");
        }

        $namespace = $this->extractNamespaceFromClass($exceptionClass);
        if ($this->isRuntime($exceptionClass, $namespace)) {
            if (!is_a($exceptionClass, '\RuntimeException', true)) {
                throw new Exceptions\InvalidExceptionHierarchy("$exceptionClass should be child of \\RuntimeException");
            }
        } else {
            if (!is_a($exceptionClass, '\LogicException', true)) {
                throw new Exceptions\InvalidExceptionHierarchy("$exceptionClass should be child of \\LogicException");
            }
        }
    }

    /**
     * @param string $className
     * @return string
     * @throws \Granam\ExceptionsHierarchy\Exceptions\MissingNamespace
     */
    protected function extractNamespaceFromClass($className)
    {
        return $this->normalizeNamespace(preg_replace('~\w+$~', '', $className));
    }

    /**
     * @param string $namespace
     * @param bool $subDir
     * @return string
     * @throws \Granam\ExceptionsHierarchy\Exceptions\MissingNamespace
     */
    protected function assembleExceptionInterfaceClass($namespace, $subDir = false)
    {
        return $this->assembleClassName($namespace, $subDir, 'Exception');
    }

    /**
     * @param string $namespace
     * @param bool $subDir
     * @return string
     * @throws \Granam\ExceptionsHierarchy\Exceptions\MissingNamespace
     */
    protected function assembleRuntimeInterfaceClass($namespace, $subDir = false)
    {
        return $this->assembleClassName($namespace, $subDir, 'Runtime');
    }

    /**
     * @param string $namespace
     * @param bool $subDir
     * @return string
     * @throws \Granam\ExceptionsHierarchy\Exceptions\MissingNamespace
     */
    protected function assembleLogicInterfaceClass($namespace, $subDir = false)
    {
        return $this->assembleClassName($namespace, $subDir, 'Logic');
    }

    /**
     * @param string $namespace
     * @param string $subDir
     * @param string $className
     * @return string
     * @throws \Granam\ExceptionsHierarchy\Exceptions\MissingNamespace
     */
    private function assembleClassName($namespace, $subDir, $className)
    {
        $namespace = $this->normalizeNamespace($namespace);

        return
            ($namespace === '\\' ? '' : $namespace)
            . ($subDir ?
                ('\\' . $subDir)
                : ''
            )
            . '\\' . $className;
    }

    /**
     * @param string $childNamespace
     * @param bool $subDirToStrip
     * @return bool|string
     * @throws \Granam\ExceptionsHierarchy\Exceptions\MissingNamespace
     */
    protected function extractParentNamespace($childNamespace, $subDirToStrip = false)
    {
        if ($childNamespace === '\\') {
            return false;
        }
        if ($subDirToStrip) {
            $childNamespace = preg_replace(
                '~[\\\]' . preg_quote($subDirToStrip, '~') . '[\\\]?$~',
                '',
                $childNamespace
            );
        }
        $roughParentNamespace = preg_replace('~[\\\]\w+[\\\]?$~', '', $childNamespace);

        return $this->normalizeNamespace($roughParentNamespace);
    }

    /**
     * @param $customExceptionClass
     * @throws \Granam\ExceptionsHierarchy\Exceptions\InvalidExceptionHierarchy
     * @throws \Granam\ExceptionsHierarchy\Exceptions\MissingNamespace
     */
    protected function My_custom_exception_follows_parent($customExceptionClass)
    {
        $closestParent = $this->getClosestParentOfSameName($customExceptionClass);
        if ($closestParent && !is_a($customExceptionClass, $closestParent, true)) {
            throw new Exceptions\InvalidExceptionHierarchy(
                "Exception {$customExceptionClass} should extends parent {$closestParent}"
            );
        }
    }

    /**
     * @param string $className
     * @return bool|string
     * @throws \Granam\ExceptionsHierarchy\Exceptions\MissingNamespace
     */
    protected function getClosestParentOfSameName($className)
    {
        $baseName = $this->extractClassBaseName($className);
        $namespace = $this->extractNamespaceFromClass($className);

        while (($namespace = $this->extractParentNamespace($namespace)) !== false) {
            $soughtParentClass = $this->assembleClassName($namespace, $this->getExceptionsSubDir(), $baseName);
            if (class_exists($soughtParentClass)) {
                return $soughtParentClass;
            }
        }

        foreach ($this->getExternalRootNamespaces() as $externalRootNamespace) {
            do {
                $soughtParentClass = $externalRootNamespace . '\\' . $baseName;
                if (class_exists($soughtParentClass)) {
                    return $soughtParentClass;
                }
            } while (($externalRootNamespace = $this->extractParentNamespace($externalRootNamespace)) !== false);
        }

        return false;
    }

    /**
     * @param $className
     * @return string
     */
    protected function extractClassBaseName($className)
    {
        preg_match('~(?<basename>\w+)$~', $className, $matches);

        return $matches['basename'];
    }

    /**
     * @param string $exceptionsUsageRootDir
     * @param array|string[] exceptionClassesSkippedFromUsageTest
     * @return bool
     * @throws \Granam\ExceptionsHierarchy\Exceptions\UnusedException
     * @throws \Granam\ExceptionsHierarchy\Exceptions\MissingNamespace
     * @throws \Granam\ExceptionsHierarchy\Exceptions\FolderCanNotBeRead
     */
    public function My_exceptions_are_used($exceptionsUsageRootDir, array $exceptionClassesSkippedFromUsageTest)
    {
        if ($exceptionsUsageRootDir !== '') {
            $exceptionsUsageRootDir = $this->normalizeDir($exceptionsUsageRootDir);
        } else {
            $exceptionsUsageRootDir = $this->getNamespaceDirectory($this->getRootNamespace());
            if ($this->getExceptionsSubDir()) {
                $exceptionsUsageRootDir = $this->normalizeDir(
                    preg_replace('~' . preg_quote($this->getExceptionsSubDir(), '~') . '$~', '', $exceptionsUsageRootDir)
                );
            }
        }
        $exceptionClassesSkippedFromUsageTest = array_map(
            function ($skippedExceptionClass) {
                return ltrim($skippedExceptionClass, '\\');
            },
            $exceptionClassesSkippedFromUsageTest
        );
        $result = true;
        $testedNamespace = $this->getTestedNamespace();
        do {
            $directory = $this->getNamespaceDirectory($testedNamespace);
            foreach ($this->getCustomExceptionsFrom($directory) as $customExceptionClass) {
                if (!in_array($customExceptionClass, $exceptionClassesSkippedFromUsageTest, true)) {
                    $result = $this->My_exception_is_used($customExceptionClass, $exceptionsUsageRootDir);
                }
            }
            $alreadyInRoot = $testedNamespace === $this->getRootNamespace();
            $testedNamespace = $this->extractParentNamespace($testedNamespace, $this->getExceptionsSubDir());
        } while (!$alreadyInRoot && $testedNamespace);

        return $result;
    }

    /**
     * @param string $exceptionClass
     * @param string $exceptionsUsageRootDir
     * @return bool
     * @throws \Granam\ExceptionsHierarchy\Exceptions\UnusedException
     * @throws \Granam\ExceptionsHierarchy\Exceptions\FolderCanNotBeRead
     */
    protected function My_exception_is_used($exceptionClass, $exceptionsUsageRootDir)
    {
        $exceptionClassBasename = preg_quote($this->extractClassBaseName($exceptionClass), '~');
        $searchForUsage = function ($dirToSearch) use (&$searchForUsage, $exceptionClassBasename) {
            foreach (new \DirectoryIterator($dirToSearch) as $folder) {
                if ($folder->isDot()) {
                    continue;
                }
                if ($folder->isFile()) {
                    if ($folder->isReadable()) {
                        $content = file_get_contents($folder->getRealPath());
                        if (preg_match('~(throw\s+new|extends|implements[\s\w\\\,]*)\s+[\w\\\]*' . $exceptionClassBasename . '[^\w\\\]+~i', $content)) {
                            return true;
                        }
                    }
                } elseif ($folder->isDir()) {
                    if ($searchForUsage($folder->getPathname())) {
                        return true;
                    }
                }
            }

            return false;
        };
        try {
            if ($searchForUsage($exceptionsUsageRootDir)) {
                return true;
            }
        } catch (\UnexpectedValueException $unexpectedValueException) {
            throw new Exceptions\FolderCanNotBeRead($unexpectedValueException->getMessage());
        }

        throw new Exceptions\UnusedException("Exception {$exceptionClass} is unused.");
    }

}