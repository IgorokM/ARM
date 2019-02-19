<?php declare(strict_types=1);

namespace Api;

use \PDO;
use \Exception;

class Db extends PDO
{
    public function __construct($file = __DIR__ . DIRECTORY_SEPARATOR . '/../setting.ini')
    {
        if (!$settingDb = parse_ini_file($file, true)) {
            throw new Exception("Unable to open {$file}");
        }
        $settingDb = $settingDb['database'];
        $options = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ];
        if (!$settingDb) {
            throw new \Exception("Setting params false");
        }

        $dsn = "{$settingDb['driver']}:dbname={$settingDb['dbname']};host={$settingDb['host']}";

        parent::__construct($dsn, $settingDb['username'], $settingDb['password'], $options);
    }
}
