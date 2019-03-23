<?php declare(strict_types=1);
namespace TestUtils;

use PHPUnit\DbUnit\Database\Connection;
use PHPUnit\DbUnit\Database\DataSet;
use PHPUnit\Framework\TestCase;

abstract class DatabaseTestCase extends TestCase
{
    private static $PDO = null;
    private $connection = null;

    abstract public function getDump() : string;

    public function getConnection() : Connection
    {
        if ($this->connection == null) {
            if (self::$PDO == null) {
                self::$PDO = new \PDO('mysql:host=mysql;charset=utf8mb4', 'root', '');
                self::$PDO->exec('CREATE DATABASE phpunit');
                self::$PDO->exec('USE phpunit');
                $setupQuery = file_get_contents($this->getDump());
                self::$PDO->exec($setupQuery);
            }
            return $this->createDefaultDBConnection(self::$PDO, 'phpunit');
        }
        return $this->connection;
    }

    public function getDataSet() : DataSet
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
