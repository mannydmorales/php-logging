<?php

use PHPUnit\Framework\TestCase;
use mannydmorales\Logging\LogPackage;
use mannydmorales\Logging\Log;
use mannydmorales\Logging\LogItem;

class LogPackageTest extends TestCase {

    private string $packagePath;
    private string $packageName;
    private LogPackage $logPackage;

    protected function setUp(): void {
        $this->packagePath = __DIR__.'/tmp';
        if(!is_dir($this->packagePath)) {
            mkdir($this->packagePath);
        }
        $this->packageName = 'test_log_package-' . time();
        $this->logPackage = new LogPackage($this->packageName, $this->packagePath);
    }

    public function testAddFile(): void {
        $filePath = __FILE__;
        $fileName = 'test_file.php';
        $this->logPackage->addFile($filePath, $fileName);

        $phar = new \PharData($this->logPackage->getLogPackageFile());
        $this->assertTrue($phar->offsetExists($fileName));
    }

    public function testGenerateLogFile(): void {
        $log = new Log();
        $log->log('Log entry 1');
        $log->log('Log entry 2');
        $logStream = $log->getLog();

        $fileName = 'log.txt';
        $this->logPackage->generateLogFile($log, $fileName);

        $phar = new \PharData($this->logPackage->getLogPackageFile());
        $this->assertTrue($phar->offsetExists($fileName));
        $this->assertStringEqualsFile('phar://' . $this->logPackage->getLogPackageFile() . '/' . $fileName, $logStream[0]->__toString().PHP_EOL.$logStream[1]->__toString().PHP_EOL);
        usleep(50000); // wait 50ms - For some reason it's deleting the file too quick
    }

    public function testAddFromString(): void {
        $data = 'This is a test string';
        $fileName = 'test_string.txt';
        $this->logPackage->addFromString($data, $fileName);

        $phar = new \PharData($this->logPackage->getLogPackageFile());
        $this->assertTrue($phar->offsetExists($fileName));
        $this->assertStringEqualsFile('phar://' . $this->logPackage->getLogPackageFile() . '/' . $fileName, $data);
    }

    protected function tearDown(): void {
        unlink($this->logPackage->getLogPackageFile());
        sleep(1); // wait 1 second for the file to be deleted - For some reason the archive is being removed too quickly
    }
}