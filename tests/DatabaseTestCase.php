<?php declare(strict_types=1);
namespace TestUtils;

abstract class DatabaseTestCase extends \PHPUnit\Framework\TestCase
{
    use \PHPUnit\DbUnit\TestCaseTrait;

    static private $PDO = null;
    private $connection = null;

    abstract public function getDump() : string;

    public function getConnection() : \PHPUnit\DbUnit\Database\Connection
    {
        if ($this->connection == null) {
            if (self::$PDO == null) {
                self::$PDO = new \PDO('mysql:host=localhost;charset=utf8mb4', 'root', '');
                self::$PDO->exec('CREATE DATABASE phpunit');
                self::$PDO->exec('USE phpunit');
                $setupQuery = file_get_contents($this->getDump());
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
