<?php declare(strict_types=1);
namespace v0_9;

global $CONFIG;

use Skaut\HandbookAPI\v0_9\Exception\ConnectionException;

class ConnectionExceptionTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @covers Skaut\HandbookAPI\v0_9\Exception\ConnectionException::__construct()
     */
    public function testCtor() : ConnectionException
    {
        $e = new ConnectionException(new \PDOException());
        $this->assertInstanceOf('\Skaut\HandbookAPI\v0_9\Exception\ConnectionException', $e);
        return $e;
    }

    /**
     * @covers Skaut\HandbookAPI\v0_9\Exception\ConnectionException::handle()
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
