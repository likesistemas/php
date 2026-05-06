<?php

use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../cli.php';

class CliTest extends TestCase {
    private $testFolder;

    protected function setUp(): void {
        $this->testFolder = __DIR__ . '/temp_test/';
        if (file_exists($this->testFolder)) {
            $this->recursiveRmdir($this->testFolder);
        }
    }

    protected function tearDown(): void {
        if (file_exists($this->testFolder)) {
            $this->recursiveRmdir($this->testFolder);
        }
    }

    public function testWriteFileBasic() {
        mkdir($this->testFolder, 0777, true);
        $content = "test content";
        testWriteFile($this->testFolder, false, $content);

        $filePath = $this->testFolder . "file.txt";
        $this->assertTrue(file_exists($filePath), "File should be created");
        $this->assertEquals($content, file_get_contents($filePath), "File content should match");
    }

    public function testWriteFileWithNewFolder() {
        $content = "new folder content";
        testWriteFile($this->testFolder, true, $content);

        $filePath = $this->testFolder . "file.txt";
        $this->assertTrue(file_exists($this->testFolder), "Folder should be created");
        $this->assertTrue(file_exists($filePath), "File should be created in new folder");
        $this->assertEquals($content, file_get_contents($filePath), "File content should match");
    }

    private function recursiveRmdir($dir) {
        if (is_dir($dir)) {
            $objects = scandir($dir);
            foreach ($objects as $object) {
                if ($object != "." && $object != "..") {
                    if (is_dir($dir . DIRECTORY_SEPARATOR . $object) && !is_link($dir . "/" . $object))
                        $this->recursiveRmdir($dir . DIRECTORY_SEPARATOR . $object);
                    else
                        unlink($dir . DIRECTORY_SEPARATOR . $object);
                }
            }
            rmdir($dir);
        }
    }
}
