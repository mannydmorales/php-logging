<?php

/**
 * Logging - A Logging Library
 * 
 * This class is a log item object that can be used to log messages.
 *
 * @package Logging
 * @version 2.0.0
 * @file    LogItem.php
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

class LogItem {

    /** @var string $message The log message. */
    public string $message;

    /** @var string $level The log level. */
    public string $level;

    /** @var int $timestamp The log timestamp. */
    public int $timestamp;

    /** @var string $defaultTimestampFormat The default timestamp format. Format accepted by DateTimeInterface::format(). */
    public static string $defaultTimestampFormat = 'Y-m-d H:i:s';

    /**
     * Constructor
     * 
     * @param string $message The log message.
     * @param string $level The log level.
     * @param int|null $timestamp The log timestamp. Default is the current time.
     */
    public function __construct(string $message, string $level, ?int $timestamp = null) {
        $this->message = $message;
        $this->level = $level;
        $this->timestamp = $timestamp ?? time();
    }

    /**
     * Get the log message.
     * 
     * @return string
     */
    public function getMessage() : string {
        return $this->message;
    }

    /**
     * Get the log level.
     * 
     * @return string
     */
    public function getLevel() : string {
        return $this->level;
    }

    /**
     * Get the log timestamp.
     * 
     * @return int
     */
    public function getTimestamp() : int {
        return $this->timestamp;
    }

    /**
     * Set the log message.
     * 
     * @param string $message The log message.
     * @return void
     */
    public function setMessage(string $message) : void {
        $this->message = $message;
    }

    /**
     * Set the log level.
     * 
     * @param string $level The log level.
     * @return void
     */
    public function setLevel(string $level) : void {
        $this->level = $level;
    }

    /**
     * Set the log timestamp.
     * 
     * @param int $timestamp The log timestamp.
     * @return void
     */
    public function setTimestamp(int $timestamp) : void {
        $this->timestamp = $timestamp;
    }

    /**
     * Get the log timestamp formatted.
     * 
     * @param string|null $format The format to use. Format accepted by DateTimeInterface::format(). Default is the default timestamp format.
     * @return string
     */
    public function getTimeFormatted(?string $format = null) : string {
        $format = $format ?? self::$defaultTimestampFormat;
        return date($format, $this->timestamp);
    }

    /**
     * Get the log item id.
     * 
     * @return string
     */
    public function getLogItemId() : string {
        return hash('sha256', $this->message.$this->level.$this->timestamp);
    }

    /**
     * Get the log item as a string.
     * 
     * @return string
     */
    public function __toString() : string {
        return sprintf('%s [%s] %s', $this->getTimeFormatted(), $this->level, $this->message);
    }

}