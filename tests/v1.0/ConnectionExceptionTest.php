<?php declare(strict_types=1);
namespace v1_0;

global $CONFIG;

use PHPUnit\Framework\TestCase;

use Skaut\HandbookAPI\v1_0\Exception\ConnectionException;

class ConnectionExceptionTest extends TestCase
{
    /**
     * @covers Skaut\HandbookAPI\v1_0\Exception\ConnectionException::__construct()
     */
    public function testCtor() : ConnectionException
    {
        $e = new ConnectionException(new \PDOException());
        $this->assertInstanceOf('\Skaut\HandbookAPI\v1_0\Exception\ConnectionException', $e);
        return $e;
    }

    /**
     * @covers Skaut\HandbookAPI\v1_0\Exception\ConnectionException::handle()
     * @depends testCtor
     */
    public function testHandle(ConnectionException $e) : void
    {
        $this->assertSame(
            [
                'status' => 500,
                'type' => 'ConnectionException',
                'message' => 'Database connection request failed. Error message: "".'
            ],
            $e->handle()
        );
    }
}
