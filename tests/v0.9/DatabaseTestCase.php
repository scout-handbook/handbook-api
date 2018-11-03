<?php declare(strict_types=1);
namespace v0_9;

use PHPUnit\Framework\TestCase;
use PHPUnit\DbUnit\TestCaseTrait;

class DatabaseTestCase extends TestCase
{
    use TestCaseTrait;

    static private $PDO = null;
    private $connection = null;

    public function getConnection() : \PHPUnit\DbUnit\Database\Connection
    {
        if ($this->connection == null) {
            if (self::$PDO == null) {
                self::$PDO = new \PDO('mysql:host=localhost;charset=utf8mb4', 'root', '');
                self::$PDO->exec('CREATE DATABASE phpunit');
                self::$PDO->exec('USE phpunit');
                $setupQuery = file_get_contents('tests/v0.9/db.sql');
                self::$PDO->exec($setupQuery);
            }
            return $this->createDefaultDBConnection(self::$PDO, 'phpunit');
        }
        return $this->connection;
    }

    public function getDataSet() : \PHPUnit\DbUnit\Database\DataSet
    {
        return $this->getConnection()->createDataSet();
    }

    public static function tearDownAfterClass() : void
    {
        parent::tearDownAfterClass();
        if (self::$PDO !== null) {
            //self::$PDO->exec('DROP DATABASE phpunit');
        }
    }
}
