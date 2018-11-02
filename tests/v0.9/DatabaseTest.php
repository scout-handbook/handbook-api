<?php declare(strict_types=1);
namespace v0_9;

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
     * @covers HandbookAPI\Database::__destruct()
     */
    public function testDtor() : void
    {
        $db = new \HandbookAPI\Database();
        $db->prepare(<<<SQL
SELECT * from lessons
SQL
        );
        unset($db);
        $this->expectOutputString('');
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
    public function testPrepare(\HandbookAPI\Database $db) : \HandbookAPI\Database
    {
        $this->assertNull($db->prepare(<<<SQL
SELECT * FROM lessons
WHERE name = :name
SQL
        ));
        return $db;
    }

    /**
     * @covers HandbookAPI\Database::prepare()
     * @expectedException HandbookAPI\QueryException
     * @depends testCtor
     */
    /*
    public function testPrepareException(\HandbookAPI\Database $db) : void
    {
        $db->prepare(<<<SQL
XELECT * FROM lessons
SQL
        );
    }
    */

    /**
     * @covers HandbookAPI\Database::bindParam()
     * @depends testPrepare
     */
    public function testBindParam($db) : \HandbookAPI\Database
    {
        $value = 'Test';
        $this->assertNull($db->bindParam('name', $value, \PDO::PARAM_STR));
        return $db;
    }

    /**
     * @covers HandbookAPI\Database::execute()
     * @depends testBindParam
     */
    public function testExecute($db) : \HandbookAPI\Database
    {
        $this->assertNull($db->execute());
        return $db;
    }

    /**
     * @covers HandbookAPI\Database::rowCount()
     * @depends testExecute
     */
    public function testRowCountZero($db) : void
    {
        $this->assertEquals(0, $db->rowCount());
    }

    /**
     * @covers HandbookAPI\Database::bindColumn()
     * @depends testExecute
     */
    public function testBindColumn($db) : void
    {
        $value;
        $this->assertNull($db->bindColumn('name', $value));
    }

    /**
     * @covers HandbookAPI\Database::fetch()
     * @depends testExecute
     */
    public function testFetchEmpty($db) : void
    {
        $this->assertFalse($db->fetch());
    }

    /**
     * @covers HandbookAPI\Database::execute()
     * @depends testCtor
     * @expectedException HandbookAPI\ExecutionException
     */
    public function testExecuteException($db) : void
    {
        $db->prepare(<<<SQL
XELECT * FROM lessons
SQL
        );
        $db->execute();
    }

    private function prepareEmpty($db) : void
    {
        $db->prepare(<<<SQL
SELECT * FROM lessons
SQL
        );
        $db->execute();
    }

    /**
     * @covers HandbookAPI\Database::fetchRequire()
     * @depends testCtor
     * @expectedException HandbookAPI\NotFoundException
     */
    public function testFetchRequireException($db) : void
    {
        $this->prepareEmpty($db);
        $db->fetchRequire('Lesson');
    }

    /**
     * @covers HandbookAPI\Database::fetchAll()
     * @depends testCtor
     */
    public function testFetchAllEmpty($db) : void
    {
        $this->prepareEmpty($db);
        $this->assertEmpty($db->fetchAll());
    }

    private function prepareNonEmpty($db) : void
    {
        $db->prepare(<<<SQL
SELECT * FROM users
SQL
        );
        $db->execute();
    }

    /**
     * @covers HandbookAPI\Database::rowCount()
     * @depends testCtor
     */
    public function testRowCountNonZero($db) : \HandbookAPI\Database
    {
        $this->prepareNonEmpty($db);
        $this->assertEquals(1, $db->rowCount());
        return $db;
    }

    /**
     * @covers HandbookAPI\Database::fetch()
     * @depends testRowCountNonZero
     */
    public function testFetchNonEmpty($db) : void
    {
        $this->assertTrue($db->fetch());
    }

    /**
     * @covers HandbookAPI\Database::fetchRequire()
     * @depends testCtor
     */
    public function testFetchRequireOk($db) : void
    {
        $this->prepareNonEmpty($db);
        $this->assertNull($db->fetchRequire('User'));
    }

    /**
     * @covers HandbookAPI\Database::fetchAll()
     * @depends testCtor
     */
    public function testFetchAllNonEmpty($db) : void
    {
        $this->prepareNonEmpty($db);
        $this->assertEquals([['id' => 125099, 'name' => 'Dědič Marek (Mlha)', 'role' => 'superuser']], $db->fetchAll());
    }

    /**
     * @covers HandbookAPI\Database::endTransaction()
     * @depends testCtor
     */
    /*
    public function testEndNonexistentTransaction($db) : void
    {
        $this->assertNull($db->endTransaction());
    }
    */

    /**
     * @covers HandbookAPI\Database::beginTransaction()
     * @depends testCtor
     */
    public function testBeginTransaction($db) : \HandbookAPI\Database
    {
        $this->assertNull($db->beginTransaction());
        return $db;
    }

    /**
     * @covers HandbookAPI\Database::endTransaction()
     * @depends testBeginTransaction
     */
    public function testEndTransaction($db) : void
    {
        $this->assertNull($db->endTransaction());
    }
}
