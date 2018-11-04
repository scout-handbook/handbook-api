<?php declare(strict_types=1);
namespace v0_9;

require_once('tests/DatabaseTestCase.php');

global $CONFIG;
require_once('v0.9/internal/Database.php');

class DatabaseTest extends \TestUtils\DatabaseTestCase
{
    public function getDump() : string
    {
        return 'tests/v0.9/db.sql';
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
    public function testBindParam(\HandbookAPI\Database $db) : \HandbookAPI\Database
    {
        $value = 'Test';
        $this->assertNull($db->bindParam('name', $value, \PDO::PARAM_STR));
        return $db;
    }

    /**
     * @covers HandbookAPI\Database::execute()
     * @depends testBindParam
     */
    public function testExecute(\HandbookAPI\Database $db) : \HandbookAPI\Database
    {
        $this->assertNull($db->execute());
        return $db;
    }

    /**
     * @covers HandbookAPI\Database::rowCount()
     * @depends testExecute
     */
    public function testRowCountZero(\HandbookAPI\Database $db) : void
    {
        $this->assertSame(0, $db->rowCount());
    }

    /**
     * @covers HandbookAPI\Database::bindColumn()
     * @depends testExecute
     */
    public function testBindColumn(\HandbookAPI\Database $db) : void
    {
        $value;
        $this->assertNull($db->bindColumn('name', $value));
    }

    /**
     * @covers HandbookAPI\Database::fetch()
     * @depends testExecute
     */
    public function testFetchEmpty(\HandbookAPI\Database $db) : void
    {
        $this->assertFalse($db->fetch());
    }

    /**
     * @covers HandbookAPI\Database::execute()
     * @depends testCtor
     * @expectedException HandbookAPI\ExecutionException
     */
    public function testExecuteException(\HandbookAPI\Database $db) : void
    {
        $db->prepare(<<<SQL
XELECT * FROM lessons
SQL
        );
        $db->execute();
    }

    private function prepareEmpty(\HandbookAPI\Database $db) : void
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
    public function testFetchRequireException(\HandbookAPI\Database $db) : void
    {
        $this->prepareEmpty($db);
        $db->fetchRequire('Lesson');
    }

    /**
     * @covers HandbookAPI\Database::fetchAll()
     * @depends testCtor
     */
    public function testFetchAllEmpty(\HandbookAPI\Database $db) : void
    {
        $this->prepareEmpty($db);
        $this->assertEmpty($db->fetchAll());
    }

    private function prepareNonEmpty(\HandbookAPI\Database $db) : void
    {
        $db->prepare(<<<SQL
SELECT * FROM users
ORDER BY id DESC
SQL
        );
        $db->execute();
    }

    /**
     * @covers HandbookAPI\Database::rowCount()
     * @depends testCtor
     */
    public function testRowCountNonZero(\HandbookAPI\Database $db) : \HandbookAPI\Database
    {
        $this->prepareNonEmpty($db);
        $this->assertSame(4, $db->rowCount());
        return $db;
    }

    /**
     * @covers HandbookAPI\Database::fetch()
     * @depends testRowCountNonZero
     */
    public function testFetchNonEmpty(\HandbookAPI\Database $db) : void
    {
        $this->assertTrue($db->fetch());
    }

    /**
     * @covers HandbookAPI\Database::fetchRequire()
     * @depends testCtor
     */
    public function testFetchRequireOk(\HandbookAPI\Database $db) : void
    {
        $this->prepareNonEmpty($db);
        $this->assertNull($db->fetchRequire('User'));
    }

    /**
     * @covers HandbookAPI\Database::fetchAll()
     * @depends testCtor
     */
    public function testFetchAllNonEmpty(\HandbookAPI\Database $db) : void
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
     * @covers HandbookAPI\Database::endTransaction()
     * @depends testCtor
     */
    public function testEndNonexistentTransaction(\HandbookAPI\Database $db) : void
    {
        $this->assertNull($db->endTransaction());
    }

    /**
     * @covers HandbookAPI\Database::beginTransaction()
     * @depends testCtor
     */
    public function testBeginTransaction(\HandbookAPI\Database $db) : \HandbookAPI\Database
    {
        $this->assertNull($db->beginTransaction());
        return $db;
    }

    /**
     * @covers HandbookAPI\Database::endTransaction()
     * @depends testBeginTransaction
     */
    public function testEndTransaction(\HandbookAPI\Database $db) : void
    {
        $this->assertNull($db->endTransaction());
    }
}
