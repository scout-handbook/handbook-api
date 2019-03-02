<?php declare(strict_types=1);
namespace v0_9;

global $CONFIG;

use PHPUnit\Framework\TestCase;

use Skaut\HandbookAPI\v0_9\Exception\QueryException;
use Skaut\HandbookAPI\v0_9\Database;

class QueryExceptionTest extends TestCase
{
    /**
     * @covers Skaut\HandbookAPI\v0_9\Exception\QueryException::__construct()
     */
    public function testCtor() : QueryException
    {
        $prop = new \ReflectionProperty('\Skaut\HandbookAPI\v0_9\Database', 'db');
        $prop->setAccessible(true);
        $db = new Database();
        $e = new QueryException('Equery', $prop->getValue($db));
        $this->assertInstanceOf('\Skaut\HandbookAPI\v0_9\Exception\QueryException', $e);
        return $e;
    }

    /**
     * @covers Skaut\HandbookAPI\v0_9\Exception\QueryException::handle()
     * @depends testCtor
     */
    public function testHandle(QueryException $e) : void
    {
        $this->assertSame(
            ['status' => 500, 'type' => 'QueryException', 'message' => 'Invalid query: "Equery". Error message: "".'],
            $e->handle()
        );
    }
}
