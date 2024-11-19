<?php

/**
 * Logging - A Logging Library
 * 
 * This class provides a simple logging library that can be used to log messages.
 *
 * @package Logging
 * @version 2.0.0
 * @file    Log.php
 * 
 * @see https://opensource.mannydmorales.com/logging The Logging Project Homepage
 *
 * @author    Manny Morales <mannydmorales@gmail.com>
 * @copyright 2007 - 2024 Manny D Morales
 * @license   MIT License
 * @note      This program is distributed in the hope that it will be useful - WITHOUT
 * ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or
 * FITNESS FOR A PARTICULAR PURPOSE.
 */

namespace mannydmorales\Logging;

class Log {

    // Log Levels - Default log levels.
    public const LEVEL_DEBUG    = 'DEBUG';
    public const LEVEL_NOTICE   = 'NOTICE';
    public const LEVEL_INFO     = 'INFO';
    public const LEVEL_WARNING  = 'WARNING';
    public const LEVEL_ERROR    = 'ERROR';
    public const LEVEL_CRITICAL = 'CRITICAL';

    /** @var int No stream. Saves log items in memory only, for retrieval. */
    public const STREAM_NONE    = 0;
    /** @var int File stream. Saves log items to a file. */
    public const STREAM_FILE    = 1;
    /** @var int Standard output stream. Logs to the console. */
    public const STREAM_STDOUT  = 2;
    /** @var int Standard error stream. Logs to the console. */
    public const STREAM_STDERR  = 3;

    /** @var string $defaultLogLevel The default log level. */
    public string $defaultLogLevel = self::LEVEL_INFO;

    /** @var array<int,LogItem> $logStream The log stream. */
    protected array $logStream = [];

    /** @var array<string,mixed> $streamOptions The stream options. */
    protected array $streamOptions = [
        'stream'        => null,
        'streamfile'    => null,
        'streamfp'      => null
    ];

    /** @var array<int,string> $logLevels The log levels for this instance. Default includes: DEBUG, NOTICE, INFO, WARNING, ERROR, CRITICAL */
    protected array $logLevels = [
        self::LEVEL_DEBUG,
        self::LEVEL_NOTICE,
        self::LEVEL_INFO,
        self::LEVEL_WARNING,
        self::LEVEL_ERROR,
        self::LEVEL_CRITICAL
    ];

    /**
     * Constructor
     * 
     * @param int $logStreamType Set the log stream type. Default is STREAM_NONE.
     * @param string|null $logStringFile Set the log file path and name. Required for file logging (STREAM_FILE).
     * @throws \InvalidArgumentException
     */
    public function __construct(int $logStreamType = self::STREAM_NONE, ?string $logStringFile = null) {
        $this->streamOptions['stream'] = $logStreamType;
        if($logStreamType === self::STREAM_FILE) {
            if($logStringFile === null) {
                throw new \InvalidArgumentException('Log file path and name is required for file logging');
            }
            $this->streamOptions['streamfile'] = $logStringFile;
            $this->streamOptions['streamfp'] = fopen($logStringFile, 'a');
        }
    }

    /**
     * Destructor
     */
    public function __destruct() {
        if($this->streamOptions['stream'] === self::STREAM_FILE) {
            fclose($this->streamOptions['streamfp']);
        }
    }

    /**
     * Set the log levels
     * 
     * @param string $logLevel
     * @return void
     */
    public function setLogLevels(string ...$logLevels) : void {
        if(count($logLevels) > 0) {
            $this->logLevels = [];
            foreach($logLevels as $logLevel) {
                if(!in_array($logLevel, $this->logLevels)) {
                    $this->logLevels[] = $logLevel;
                }
            }
        }
    }

    /**
     * Get the Stream Type.
     * 
     * @return int
     */
    public function getLogStreamType() : int {
        return $this->streamOptions['stream'];
    }

    /**
     * Stream the log item.
     * 
     * @param LogItem $logItem
     * @return void
     */
    protected function streamLog(LogItem $logItem) : void {
        $this->logStream[] = $logItem;
        if($this->streamOptions['stream'] === self::STREAM_NONE) {
            return;
        }
        if($this->streamOptions['stream'] === self::STREAM_STDOUT) {
            echo $logItem.PHP_EOL;
            return;
        }
        if($this->streamOptions['stream'] === self::STREAM_STDERR) {
            fwrite(STDERR, $logItem.PHP_EOL);
            return;
        }
        if($this->streamOptions['stream'] === self::STREAM_FILE) {
            fwrite($this->streamOptions['streamfp'], $logItem.PHP_EOL);
            return;
        }
    }

    /**
     * Log a message.
     * 
     * @param LogItem|string $message The LogItem object or the message to log (will create a LogItem object if message is a string).
     * @param string|null $level Set the log level. If not set, the default log level is used.
     * @return void
     * @throws \InvalidArgumentException
     */
    public function log(LogItem|string $message, ?string $level = null) : void {
        $msg = null;
        if($message instanceof LogItem) {
            $level = $message->getLevel();
            $msg = $message;
        } else {
            if($level === null) {
                $level = $this->defaultLogLevel;
            }
            $msg = new LogItem($message, $level);
        }
        if(in_array($level, $this->logLevels)) {
            $this->streamLog($msg);
            return;
        }
        throw new \InvalidArgumentException('Invalid log level: '.$level);
    }

    /**
     * Get the log stream. 
     * 
     * @return array<int,LogItem>
     */
    public function getLog() : array {
        return $this->logStream;
    }

    /**
     * Get the log stream by log level.
     * 
     * @param string|array<int,string> $level
     * @return array<int,LogItem>
     * @throws \InvalidArgumentException
     */
    public function getLogByLevel(string|array $level) : array {
        if(is_string($level)) {
            $level = [$level];
        }
        foreach($level as $logLevel) {
            if(!in_array($logLevel, $this->logLevels)) {
                throw new \InvalidArgumentException('Invalid log level: '.$logLevel);
            }
        }
        $logs = [];
        foreach($this->logStream as $logItem) {
            if(in_array($logItem->getLevel(), $level)) {
                $logs[] = $logItem;
            }
        }
        return $logs;
    }

}