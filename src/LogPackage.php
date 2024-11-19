<?php

/**
 * Logging - A Logging Library
 * 
 * This class creates a log package (archive) that can be used to store log(s) and artifacts.
 *
 * @package Logging
 * @version 2.0.0
 * @file    LogPackage.php
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

class LogPackage {

    /** @var \PharData $package The package object. */
    private \PharData $package;

    /** @var string $packagePath The directory to save the package to. */
    private string $packagePath;

    /** @var string $packageName The name of the package. */
    private string $packageName;

    /**
     * Constructor
     * 
     * @param string $packageName The name of the package.
     * @param string $packagePath The directory to the save the package to.
     */
    public function __construct(string $packageName, string $packagePath) {
        $this->packageName = $packageName;
        $this->packagePath = $packagePath;
        $this->package = new \PharData($this->packagePath . '/' . $this->packageName . '.tar');
    }

    /**
     * Add a file to the package.
     * 
     * @param string $filePath The path to the file to add.
     * @param string $fileName The path/name of the file in the archive.
     * @return void
     */
    public function addFile(string $filePath, string $fileName) : void {
        $this->package->addFile($filePath, $fileName);
    }

    /**
     * Generate a log file from a Log object then add it to the package.
     * 
     * @param Log $log The log object to add.
     * @param string $fileName The path/name of the file in the archive.
     * @return void
     */
    public function generateLogFile(Log &$log, string $fileName) : void {
        $logContents = $log->getLog();
        $logContent = '';
        foreach($logContents as $logItem) {
            $logContent .= $logItem->__toString() . PHP_EOL;
        }
        $this->package->addFromString($fileName, $logContent);
    }

    /**
     * Add a string to the package.
     * 
     * @param string $data The data to add.
     * @param string $fileName The path/name of the file in the archive.
     * @return void
     */
    public function addFromString(string $data, string $fileName) : void {
        $this->package->addFromString($fileName, $data);
    }

    /**
     * Get the log package file.
     * 
     * @return string
     */
    public function getLogPackageFile() : string {
        return $this->packagePath . '/' . $this->packageName . '.tar';
    }

}