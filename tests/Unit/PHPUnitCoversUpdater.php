<?php

namespace WPframework\Tests\Unit;

class PHPUnitCoversUpdater
{
    protected $testDir;
    protected $srcNamespace;
    protected $namingConvention;
    protected $testFiles = [];

    /**
     * Constructor for the updater.
     * @param string $testDir The root directory of the test files.
     * @param string $srcNamespace The base namespace for the source code being tested.
     * @param string $namingConvention Naming convention: 'camelCase' or 'snakeCase'.
     */
    public function __construct(string $testDir, string $srcNamespace, string $namingConvention = 'camelCase')
    {
        $this->testDir = $testDir;
        $this->srcNamespace = $srcNamespace;
        $this->namingConvention = $namingConvention;
		$this->setTestFilesRecursive($this->testDir);
    }

    /**
     * Update @covers annotations for all test files.
     */
    public function updateCoversAnnotations()
    {
        foreach ($this->testFiles as $testFile) {
            $this->processTestFile($testFile);
        }
    }

    public function getTestFiles()
    {
        return $this->testFiles;
    }

    /**
     * Get the list of test files by recursively searching through directories.
     * @return array List of test file paths.
     */
    protected function setTestFilesRecursive($testDir)
    {
        $iterator = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($testDir));

        foreach ($iterator as $file) {
            if ($file->isFile() && preg_match('/Test\.php$/', $file->getFilename())) {
                $this->testFiles[] = $file->getPathname();
            }
        }

        return $this->testFiles;
    }

    /**
     * Process a test file and add @covers annotations based on the naming convention.
     * @param string $filePath The full path to the test file.
     */
    protected function processTestFile($filePath)
    {
        require_once $filePath;

        $className = $this->getClassNameFromFile($filePath).'Test';
        if (!$className) {
            return;
        }

        $reflectionClass = new \ReflectionClass($className);
        $methods = $reflectionClass->getMethods(\ReflectionMethod::IS_PUBLIC);

        $testFileContent = file_get_contents($filePath);

        foreach ($methods as $method) {
            if (strpos($method->getName(), 'test') === 0) {
                $this->addCoversAnnotation($method, $testFileContent, $filePath);
            }
        }
    }

    /**
     * Add the @covers annotation to a test method.
     * @param \ReflectionMethod $method The reflection method for the test.
     * @param string &$fileContent The current content of the test file.
     * @param string $filePath The file path of the test file.
     */
    protected function addCoversAnnotation(\ReflectionMethod $method, &$fileContent, $filePath)
    {
        $methodName = $method->getName();
        $className = null;
        $methodToTest = null;

        // Handle camelCase or snake_case conventions
        if ($this->namingConvention === 'camelCase') {
            $className = $this->getClassNameFromTestMethod($methodName);
            $methodToTest = $this->getMethodFromTestMethod($methodName);
        } elseif ($this->namingConvention === 'snakeCase') {
            list($className, $methodToTest) = $this->getClassAndMethodFromUnderscoreTestMethod($methodName);
        }

        if (!$className || !$methodToTest) {
             // Skip if we cannot determine the class or method
            return;
        }

        $coversAnnotation = sprintf("@covers \\%s::%s\n", $className, $methodToTest);

        $docComment = $method->getDocComment();

        if ($docComment && strpos($docComment, '@covers') === false) {
            // Add @covers annotation to existing docblock
            $newDocComment = str_replace('/**', "/**\n * $coversAnnotation", $docComment);
            $fileContent = str_replace($docComment, $newDocComment, $fileContent);
            dump("New Doc Comment: $$newDocComment");
        } elseif (!$docComment) {
            // No docblock exists, create a new one
            $startPos = strpos($fileContent, "public function {$methodName}(");
            $newDocBlock = "/**\n * $coversAnnotation */\n";
            $fileContent = substr_replace($fileContent, $newDocBlock, $startPos, 0);
            dump("New Doc Block: $newDocBlock");
        }

        //dump("Annotation Updated: $filePath");

        // Save changes to the test file
        //file_put_contents($filePath, $fileContent);
    }

    /**
     * Get the class name being tested from the test file path.
     * @param string $filePath The test file path.
     * @return string|null The class name or null if it cannot be determined.
     */
    protected function getClassNameFromFile($filePath)
    {
        // Assumes PSR-4: convert file path to namespace (example: tests/FooTest.php -> WPframework\Tests\Unit\Foo)
        $relativePath = str_replace([$this->testDir . '/', 'Test.php'], '', $filePath);
        return $this->srcNamespace . '\\' . str_replace('/', '\\', $relativePath);
    }

    /**
     * Get the class name from camelCase test methods.
     * Example: testDoSomethingFromClassName => ClassName
     * @param string $methodName The test method name.
     * @return string|null The class name.
     */
    protected function getClassNameFromTestMethod($methodName)
    {
        if (preg_match('/test.*From(\w+)/', $methodName, $matches)) {
            return $this->srcNamespace . '\\' . $matches[1];
        }
        return null;
    }

    /**
     * Get the method name from camelCase test methods.
     * Example: testDoSomethingFromClassName => doSomething
     * @param string $methodName The test method name.
     * @return string The method name.
     */
    protected function getMethodFromTestMethod($methodName)
    {
        return lcfirst(str_replace('test', '', preg_replace('/From\w+/', '', $methodName)));
    }

    /**
     * Get the class and method from snake_case test methods.
     * Example: test_config_function_with_valid_key => Config::function
     * @param string $methodName The test method name.
     * @return array [className, methodToTest] or [null, null].
     */
    protected function getClassAndMethodFromUnderscoreTestMethod($methodName)
    {
        $parts = explode('_', $methodName);
        if (count($parts) >= 3) {
            $className = ucfirst($parts[1]);
            $methodToTest = $parts[2];
            return [$this->srcNamespace . '\\' . $className, $methodToTest];
        }
        return [null, null];
    }
}
