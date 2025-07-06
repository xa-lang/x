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

    private function getDurationForHuman($t_secs) {
        $t_ms = 1000 * $t_secs;

        if ($t_ms < 1000) {
            return round($t_ms, 3) . "ms";
        } elseif ($t_ms < 60_000) {
            return round($t_secs, 3) . "s";
        } elseif ($t_ms < 360_000) {
            return round($t_secs / 60, 3) . "m";
        } else {
            return round($t_secs / 60, 3) . "hr";
        }
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
        $t_start = microtime(true);

        $content = file_get_contents($file);
        $sections = $this->parseTestFile($content);

        if (!$sections) {
            echo "  \033[1;30;42m FAIL \033[0m {$file} - Invalid test file format\n";
            $this->failed++;
            return;
        }

        $testName = $sections['TEST'];
        $source = $sections['SRC'];
        $expected = implode(
            "\n",
            array_map(
                fn ($v) => "    $v",
                explode("\n", $sections['EXPECT'] ?? '')
                )
        );

        $this->lexer = new Lexer($source);
        $actual = implode(
            "\n",
            array_map(
                fn ($v) => "    $v",
                explode("\n", $this->lexer->generateTokensOut())
            )
        );

        if ($actual === $expected) {
            echo "  \033[1;30;42m PASS \033[0m {$testName}\n\n";
            $this->passed++;
        } else {
            echo <<<X_TESTS
              \033[0;30;41m FAIL \033[0m {$testName}
              Expected:
            \033[1m{$expected}\033[0m
              Got:
            \033[0;31m{$actual}\033[0m
            
            X_TESTS;
            $this->failed++;
        }

        $t_stop = microtime(true);
        return $t_stop - $t_start;
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

        echo "\n";
        $t_seconds = 0;
        foreach ($files as $file) {
            if (file_exists($file)) {
                $t_seconds += $this->runTestFile($file);
            }
        }

        $t_duration = $this->getDurationForHuman($t_seconds);
        echo <<<X_TESTS

          Tests: {$this->passed} passed, {$this->failed} failed
          Duration: {$t_duration}
        \n
        X_TESTS;
    }
}
