<?php

use PHPUnit\Framework\TestCase;
use mannydmorales\Logging\Log;
use mannydmorales\Logging\LogItem;

class LogTest extends TestCase {

    public function testConstructorWithDefaultValues() {
        $log = new Log();
        $this->assertEquals(Log::STREAM_NONE, $log->getLogStreamType());
        $this->assertEmpty($log->getLog());
    }

    public function testConstructorWithFileStream() {
        $logFile = '/tmp/test_log.log';
        $log = new Log(Log::STREAM_FILE, $logFile);
        $this->assertEquals(Log::STREAM_FILE, $log->getLogStreamType());
        $this->assertFileExists($logFile);
        unlink($logFile);
    }

    public function testConstructorWithInvalidFileStream() {
        $this->expectException(\InvalidArgumentException::class);
        new Log(Log::STREAM_FILE);
    }

    public function testLogWithDefaultLevel() {
        $log = new Log();
        $log->log('Test message');
        $logStream = $log->getLog();
        $this->assertCount(1, $logStream);
        $this->assertEquals('Test message', $logStream[0]->getMessage());
        $this->assertEquals(Log::LEVEL_INFO, $logStream[0]->getLevel());
    }

    public function testLogWithCustomLevel() {
        $log = new Log();
        $log->log('Test message', Log::LEVEL_ERROR);
        $logStream = $log->getLog();
        $this->assertCount(1, $logStream);
        $this->assertEquals('Test message', $logStream[0]->getMessage());
        $this->assertEquals(Log::LEVEL_ERROR, $logStream[0]->getLevel());
    }

    public function testLogWithInvalidLevel() {
        $log = new Log();
        $this->expectException(\InvalidArgumentException::class);
        $log->log('Test message', 'INVALID_LEVEL');
    }

    public function testGetLogByLevel() {
        $log = new Log();
        $log->log('Debug message', Log::LEVEL_DEBUG);
        $log->log('Info message', Log::LEVEL_INFO);
        $log->log('Error message', Log::LEVEL_ERROR);

        $debugLogs = $log->getLogByLevel(Log::LEVEL_DEBUG);
        $this->assertCount(1, $debugLogs);
        $this->assertEquals('Debug message', $debugLogs[0]->getMessage());

        $errorLogs = $log->getLogByLevel(Log::LEVEL_ERROR);
        $this->assertCount(1, $errorLogs);
        $this->assertEquals('Error message', $errorLogs[0]->getMessage());
    }

    public function testGetLogByInvalidLevel() {
        $log = new Log();
        $this->expectException(\InvalidArgumentException::class);
        $log->getLogByLevel('INVALID_LEVEL');
    }

    public function testSetLogLevels() {
        $log = new Log();
        $log->setLogLevels(Log::LEVEL_ERROR, Log::LEVEL_CRITICAL);
        $log->log('Error message', Log::LEVEL_ERROR);
        $log->log('Critical message', Log::LEVEL_CRITICAL);
        $this->expectException(\InvalidArgumentException::class);
        $log->log('Info message', Log::LEVEL_INFO);

        $logStream = $log->getLog();
        $this->assertCount(2, $logStream);
        $this->assertEquals('Error message', $logStream[0]->getMessage());
        $this->assertEquals('Critical message', $logStream[1]->getMessage());
    }

}