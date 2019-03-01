<?php declare(strict_types=1);
namespace v0_9;

require_once('tests/DatabaseTestCase.php');

global $CONFIG;

use Skaut\HandbookAPI\v0_9\Database;

class DatabaseTest extends \TestUtils\DatabaseTestCase
{
    public function getDump() : string
    {
        return 'tests/v0.9/db.sql';
    }

    /**
     * @covers Skaut\HandbookAPI\v0_9\Database::__destruct()
     */
    public function testDtor() : void
    {
        $db = new Database();
        $db->prepare(<<<SQL
SELECT * from lessons
SQL
        );
        unset($db);
        $this->expectOutputString('');
    }

    /**
     * @covers Skaut\HandbookAPI\v0_9\Database::__construct()
     */
    public function testCtor() : Database
    {
        $db = new Database();
        $this->assertInstanceOf('\Skaut\HandbookAPI\v0_9\Database', $db);
        return $db;
    }

    /**
     * @covers Skaut\HandbookAPI\v0_9\Database::prepare()
     * @depends testCtor
     */
    public function testPrepare(Database $db) : Database
    {
        $this->assertNull($db->prepare(<<<SQL
SELECT * FROM lessons
WHERE name = :name
SQL
        ));
        return $db;
    }

    /**
     * @covers Skaut\HandbookAPI\v0_9\Database::prepare()
     * @expectedException Skaut\HandbookAPI\v0_9\Exception\QueryException
     * @depends testCtor
     */
    /*
    public function testPrepareException(Database $db) : void
    {
        $db->prepare(<<<SQL
XELECT * FROM lessons
SQL
        );
    }
    */

    /**
     * @covers Skaut\HandbookAPI\v0_9\Database::bindParam()
     * @depends testPrepare
     */
    public function testBindParam(Database $db) : Database
    {
        $value = 'Test';
        $this->assertNull($db->bindParam('name', $value, \PDO::PARAM_STR));
        return $db;
    }

    /**
     * @covers Skaut\HandbookAPI\v0_9\Database::execute()
     * @depends testBindParam
     */
    public function testExecute(Database $db) : Database
    {
        $this->assertNull($db->execute());
        return $db;
    }

    /**
     * @covers Skaut\HandbookAPI\v0_9\Database::rowCount()
     * @depends testExecute
     */
    public function testRowCountZero(Database $db) : void
    {
        $this->assertSame(0, $db->rowCount());
    }

    /**
     * @covers Skaut\HandbookAPI\v0_9\Database::bindColumn()
     * @depends testExecute
     */
    public function testBindColumn(Database $db) : void
    {
        $value;
        $this->assertNull($db->bindColumn('name', $value));
    }

    /**
     * @covers Skaut\HandbookAPI\v0_9\Database::fetch()
     * @depends testExecute
     */
    public function testFetchEmpty(Database $db) : void
    {
        $this->assertFalse($db->fetch());
    }

    /**
     * @covers Skaut\HandbookAPI\v0_9\Database::execute()
     * @depends testCtor
     * @expectedException Skaut\HandbookAPI\v0_9\Exception\ExecutionException
     */
    public function testExecuteException(Database $db) : void
    {
        $db->prepare(<<<SQL
XELECT * FROM lessons
SQL
        );
        $db->execute();
    }

    private function prepareEmpty(Database $db) : void
    {
        $db->prepare(<<<SQL
SELECT * FROM lessons
SQL
        );
        $db->execute();
    }

    /**
     * @covers Skaut\HandbookAPI\v0_9\Database::fetchRequire()
     * @depends testCtor
     * @expectedException Skaut\HandbookAPI\v0_9\Exception\NotFoundException
     */
    public function testFetchRequireException(Database $db) : void
    {
        $this->prepareEmpty($db);
        $db->fetchRequire('Lesson');
    }

    /**
     * @covers Skaut\HandbookAPI\v0_9\Database::fetchAll()
     * @depends testCtor
     */
    public function testFetchAllEmpty(Database $db) : void
    {
        $this->prepareEmpty($db);
        $this->assertEmpty($db->fetchAll());
    }

    private function prepareNonEmpty(Database $db) : void
    {
        $db->prepare(<<<SQL
SELECT * FROM users
ORDER BY id DESC
SQL
        );
        $db->execute();
    }

    /**
     * @covers Skaut\HandbookAPI\v0_9\Database::rowCount()
     * @depends testCtor
     */
    public function testRowCountNonZero(Database $db) : Database
    {
        $this->prepareNonEmpty($db);
        $this->assertSame(4, $db->rowCount());
        return $db;
    }

    /**
     * @covers Skaut\HandbookAPI\v0_9\Database::fetch()
     * @depends testRowCountNonZero
     */
    public function testFetchNonEmpty(Database $db) : void
    {
        $this->assertTrue($db->fetch());
    }

    /**
     * @covers Skaut\HandbookAPI\v0_9\Database::fetchRequire()
     * @depends testCtor
     */
    public function testFetchRequireOk(Database $db) : void
    {
        $this->prepareNonEmpty($db);
        $this->assertNull($db->fetchRequire('User'));
    }

    /**
     * @covers Skaut\HandbookAPI\v0_9\Database::fetchAll()
     * @depends testCtor
     */
    public function testFetchAllNonEmpty(Database $db) : void
    {
        $this->prepareNonEmpty($db);
        $this->assertSame(
            [
                ['id' => '125099', 'name' => 'Superuser user', 'role' => 'superuser'],
                ['id' => '125098', 'name' => 'Administrator user', 'role' => 'administrator'],
                ['id' => '125097', 'name' => 'Editor user', 'role' => 'editor'],
                ['id' => '125096', 'name' => 'User user', 'role' => 'user']
            ],
            $db->fetchAll()
        );
    }

    /**
     * @covers Skaut\HandbookAPI\v0_9\Database::endTransaction()
     * @depends testCtor
     * @expectedException Skaut\HandbookAPI\v0_9\Exception\ConnectionException
     */
    public function testEndNonexistentTransaction(Database $db) : void
    {
        $this->assertNull($db->endTransaction());
    }

    /**
     * @covers Skaut\HandbookAPI\v0_9\Database::beginTransaction()
     * @depends testCtor
     */
    public function testBeginTransaction(Database $db) : Database
    {
        $this->assertNull($db->beginTransaction());
        return $db;
    }

    /**
     * @covers Skaut\HandbookAPI\v0_9\Database::endTransaction()
     * @depends testBeginTransaction
     */
    public function testEndTransaction(Database $db) : void
    {
        $this->assertNull($db->endTransaction());
    }
}
