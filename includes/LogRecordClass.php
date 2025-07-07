<?php
class Logger {
    private static $logFile;

    public static function log($message) {
        $date = date('Y-m-d');
        $logFile = self::getLogFile($date);
        $timestamp = date('Y-m-d H:i:s');
        $logMessage = "[$timestamp] $message" . PHP_EOL;
        file_put_contents($logFile, $logMessage, FILE_APPEND | LOCK_EX);
    }

    private static function getLogFile($date) {
        if (empty(self::$logFile)) {
            self::$logFile = "logs/$date.log";
            if (!file_exists('logs')) {
                mkdir('logs', 0755, true);
            }
        }
        return self::$logFile;
    }
}