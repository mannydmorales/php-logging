<?php

use mannydmorales\Logging\Log;
use PHPUnit\Framework\TestCase;
use mannydmorales\Logging\LogItem;

class LogItemTest extends TestCase {

    public function testConstructor() {
        $logItem = new LogItem('Test message', 'INFO');
        $this->assertEquals('Test message', $logItem->getMessage());
        $this->assertEquals('INFO', $logItem->getLevel());
        $this->assertIsInt($logItem->getTimestamp());
    }

    public function testSetMessage() {
        $logItem = new LogItem('Test message', 'INFO');
        $logItem->setMessage('New message');
        $this->assertEquals('New message', $logItem->getMessage());
    }

    public function testSetLevel() {
        $logItem = new LogItem('Test message', 'INFO');
        $logItem->setLevel('ERROR');
        $this->assertEquals('ERROR', $logItem->getLevel());
    }

    public function testSetTimestamp() {
        $logItem = new LogItem('Test message', 'INFO');
        $newTimestamp = time() + 1000;
        $logItem->setTimestamp($newTimestamp);
        $this->assertEquals($newTimestamp, $logItem->getTimestamp());
    }

    public function testGetTimeFormatted() {
        $logItem = new LogItem('Test message', 'INFO', mktime(0,0,0,10,1,2021));
        $this->assertEquals(date(LogItem::$defaultTimestampFormat, mktime(0,0,0,10,1,2021)), $logItem->getTimeFormatted());
        $newFormat = 'Y-m-d H:i:s A';
        $this->assertEquals(date($newFormat, mktime(0,0,0,10,1,2021)), $logItem->getTimeFormatted($newFormat));
    }

    public function testGetLogItemId() {
        $logItem = new LogItem('Test message', 'INFO', 1633072800);
        $expectedHash = hash('sha256', 'Test messageINFO1633072800');
        $this->assertEquals($expectedHash, $logItem->getLogItemId());
    }

    public function testToString() {
        $logItem = new LogItem('Test message', 'INFO', mktime(0,0,0,10,1,2021));
        $this->assertEquals('2021-10-01 00:00:00 [INFO] Test message', (string)$logItem);
    }
}