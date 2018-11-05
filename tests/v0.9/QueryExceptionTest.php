<?php declare(strict_types=1);
namespace v0_9;

global $CONFIG;
require_once('v0.9/internal/exceptions/QueryException.php');
require_once('v0.9/internal/Database.php');

class QueryExceptionTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @covers HandbookAPI\QueryException::__construct()
     */
    public function testCtor() : \HandbookAPI\QueryException
    {
        $prop = new \ReflectionProperty('\HandbookAPI\Database', 'db');
        $prop->setAccessible(true);
        $db = new \HandbookAPI\Database();
        $e = new \HandbookAPI\QueryException('Equery', $prop->getValue($db));
        $this->assertInstanceOf('\HandbookAPI\QueryException', $e);
        return $e;
    }

    /**
     * @covers HandbookAPI\QueryException::handle()
     * @depends testCtor
     */
    public function testHandle(\HandbookAPI\QueryException $e) : void
    {
        $this->assertSame(
            ['status' => 500, 'type' => 'QueryException', 'message' => 'Invalid query: "Equery". Error message: "".'],
            $e->handle()
        );
    }
}
