<?php

require_once __DIR__ . '/../cli.php';

class CliTest {
    private $testDir;

    public function __construct() {
        $this->testDir = __DIR__ . '/temp_test/';
    }

    public function setup() {
        if (file_exists($this->testDir)) {
            $this->recursiveRemove($this->testDir);
        }
    }

    private function recursiveRemove($dir) {
        $files = array_diff(scandir($dir), array('.', '..'));
        foreach ($files as $file) {
            (is_dir("$dir/$file")) ? $this->recursiveRemove("$dir/$file") : unlink("$dir/$file");
        }
        return rmdir($dir);
    }

    public function testDefaultContent() {
        echo "Running testDefaultContent...\n";
        testWriteFile($this->testDir, true);
        $file = $this->testDir . 'file.txt';
        if (!file_exists($file)) {
            throw new Exception("File was not created.");
        }
        if (file_get_contents($file) !== '123') {
            throw new Exception("File content mismatch.");
        }
        echo "testDefaultContent passed!\n";
    }

    public function testCustomContent() {
        echo "Running testCustomContent...\n";
        $content = "Hello World";
        testWriteFile($this->testDir, true, $content);
        $file = $this->testDir . 'file.txt';
        if (file_get_contents($file) !== $content) {
            throw new Exception("File custom content mismatch.");
        }
        echo "testCustomContent passed!\n";
    }

    public function run() {
        $methods = get_class_methods($this);
        foreach ($methods as $method) {
            if (strpos($method, 'test') === 0) {
                $this->setup();
                $this->$method();
            }
        }
    }
}

try {
    $test = new CliTest();
    $test->run();
    echo "\nAll tests in CliTest passed successfully!\n";
} catch (Exception $e) {
    echo "\nTest failed: " . $e->getMessage() . "\n";
    exit(1);
}
