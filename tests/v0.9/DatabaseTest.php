<?php declare(strict_types=1);
namespace v0_9Tests;

use PHPUnit\Framework\TestCase;
use PHPUnit\DbUnit\TestCaseTrait;

define('HandbookAPI\_API_EXEC', 1);
define('_API_EXEC', 1);
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

    /*
    public function setUp() : void
    {
        parent::setUp();
        vfsStreamWrapper::register();
        $root = new vfsStreamDirectory('root');
        vfsStreamWrapper::setRoot($root);
        vfsStreamWrapper::newFile('api-config.php')->at($root)->setContent(file_get_contents('api-config.php.sample'));
    }
     */

    public static function tearDownAfterClass() : void
    {
        parent::tearDownAfterClass();
        if (self::$PDO !== null) {
            self::$PDO->exec('DROP DATABASE phpunit');
        }
    }

    public function testCtor() : void
    {
        $this->assertInstanceOf('\HandbookAPI\Database', new \HandbookAPI\Database());
    }
}
