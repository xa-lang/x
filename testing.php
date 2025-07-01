<?php

class Testing
{
    private string $testDir;
    private Lexer $lexer;
    private int $passed;
    private int $failed;

    public function __construct($testsDir)
    {
        $this->testDir = rtrim($testsDir, '/');
        $this->passed = 0;
        $this->failed = 0;
    }

    private function getAllTestFiles()
    {
        $files = [];
        $iterator = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator(
                $this->testDir,
                RecursiveDirectoryIterator::SKIP_DOTS
            ),
            RecursiveIteratorIterator::SELF_FIRST,
        );

        foreach ($iterator as $file) {
            if ($file->isFile() && preg_match('/.xt$/', $file->getPathname())) {
                $files[] = $file->getPathname();
            }
        }

        sort($files);
        return $files;
    }

    private function parseTestFile($content)
    {
        $sections = ['TEST' => '', 'SRC' => '', 'EXPECT' => ''];
        $sectionsList = ['TEST' => [], 'SRC' => [], 'EXPECT' => []];
        $currentSection = null;

        foreach (explode("\n", $content) as $line) {
            if (preg_match('/^---([A-Z]+)---$/', $line, $matches)) {
                $currentSection = $matches[1];
                continue;
            }
            if ($currentSection && isset($sectionsList[$currentSection])) {
                $sectionsList[$currentSection][] = $line;
            }
        }

        foreach ($sectionsList as $key => $value) {
            if (($key === 'TEST' || $key === 'SRC') && empty($sectionsList[$key])) {
                return false;
            }
            $sections[$key] = join("\n", $value);
        }

        if (empty($sections['EXPECT'])) {
            return false;
        }

        return $sections;
    }

    private function runTestFile($file)
    {
        $content = file_get_contents($file);
        $sections = $this->parseTestFile($content);

        if (!$sections) {
            echo "FAIL: {$file} - Invalid test file format\n";
            $this->failed++;
            return;
        }

        $testName = $sections['TEST'];
        $source = $sections['SRC'];
        $expected = $sections['EXPECT'] ?? '';

        echo "Running test: {$testName}\n";

        $this->lexer = new Lexer($source);
        $actual = $this->lexer->generateTokensOut();

        if ($actual === $expected) {
            echo "PASS: {$testName}\n";
            $this->passed++;
        } else {
            echo "FAIL: {$testName}\n";
            echo "Expected:\n{$expected}\n";
            echo "Got:\n{$actual}\n";
            $this->failed++;
        }
    }

    public function runTests($specificTest = null)
    {
        if ($specificTest) {
            $file = realpath($this->testDir . '/' . $specificTest);
            if (!file_exists($file) || !preg_match('/\.test$/', $file)) {
                echo "Error: Test file {$specificTest} not found or not a .test file\n";
                return;
            }
            $files = [$file];
        } else {
            $files = $this->getAllTestFiles();
        }

        if (empty($files)) {
            echo "No .xt files found in {$this->testDir}\n";
            return;
        }

        echo "Running xlang tests...\n\n";

        foreach ($files as $file) {
            if (file_exists($file)) {
                $this->runTestFile($file);
            }
        }

        echo "\nTest Summary: Passed: {$this->passed}, Failed: {$this->failed}\n";
    }
}
