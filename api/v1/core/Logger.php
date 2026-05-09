<?php

namespace Api\V1\Core;

class Logger {
    private static $logFile = __DIR__ . '/../../logs/app.log';

    public static function log($message, $level = 'INFO') {
        $dir = dirname(self::$logFile);
        if (!is_dir($dir)) {
            mkdir($dir, 0777, true);
        }

        $date = date('Y-m-d H:i:s');
        $trace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 1)[0];
        $file = basename($trace['file']);
        $line = $trace['line'];
        
        $logEntry = "[$date] [$level] [$file:$line] $message" . PHP_EOL;
        file_put_contents(self::$logFile, $logEntry, FILE_APPEND);
    }

    public static function error($message) {
        self::log($message, 'ERROR');
    }

    public static function warning($message) {
        self::log($message, 'WARNING');
    }

    public static function getLogs($limit = 100) {
        if (!file_exists(self::$logFile)) return [];
        $lines = file(self::$logFile);
        return array_slice(array_reverse($lines), 0, $limit);
    }

    public static function clearLogs() {
        if (file_exists(self::$logFile)) {
            unlink(self::$logFile);
        }
    }
}
