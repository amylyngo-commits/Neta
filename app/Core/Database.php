<?php
namespace App\Core;
use PDO;
class Database {
    private static ?PDO $pdo = null;
    public static function connection(): PDO {
        if (self::$pdo === null) {
            $c = $GLOBALS['config']['database'] ?? (require __DIR__.'/../../config/config.php')['database'];
            $dsn = "mysql:host={$c['host']};dbname={$c['name']};charset={$c['charset']}";
            self::$pdo = new PDO($dsn, $c['user'], $c['password'], [PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION, PDO::ATTR_DEFAULT_FETCH_MODE=>PDO::FETCH_ASSOC, PDO::ATTR_EMULATE_PREPARES=>false]);
        }
        return self::$pdo;
    }
}
