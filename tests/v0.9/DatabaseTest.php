<?php declare(strict_types=1);
namespace v0_9Tests;

use PHPUnit\Framework\TestCase;
use PHPUnit\DbUnit\TestCaseTrait;

require_once('v0.9/internal/Database.php');

class DatabaseTest extends TestCase
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
            self::$PDO->exec('DROP DATABASE phpunit');
        }
    }

    /**
     * @covers HandbookAPI\Database::__construct()
     */
    public function testCtor() : \HandbookAPI\Database
    {
        $db = new \HandbookAPI\Database();
        $this->assertInstanceOf('\HandbookAPI\Database', $db);
        return $db;
    }

    /**
     * @covers HandbookAPI\Database::prepare()
     * @depends testCtor
     */
    public function testPrepare(\HandbookAPI\Database $db) : void
    {
        $this->assertNull($db->prepare('SELECT * FROM lessons'));
    }
}
