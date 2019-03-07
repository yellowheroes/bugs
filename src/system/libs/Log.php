<?php
namespace yellowheroes\bugs\system\libs;

/**
 * Use class Log to keep logs
 * @param string $fileName  the filename of the log-file
 * @param string $logMsg    the message to store in the log-file
 * 
 */

class Log
{
    public function __construct()
    {
    }
    
    public function store($fileName = null, $logMsg = null, $prepend = true)
    {
        // null coalescing ?? - ternary in conjuction with isset() - returns first operand if exists and not null
        $logDir = \dirname(__DIR__, 1) . '/logs/';
        $logFile = $fileName ?? 'eventlog';
        $logFile = $logDir . $logFile . '.txt';
        //$handle = \fopen($logFile, 'a+b');
        $time = \date("Y-m-d H:i:s");
        $logMsg = $time . "\n" . $logMsg . "\n\n";
        
        $logFileContents = \file_get_contents($logFile);
        $logMsg = ($prepend === true) ? $logMsg . $logFileContents : $logFileContents . $logMsg;
        \file_put_contents($logFile, $logMsg);
    }  
}
