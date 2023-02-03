<?php declare(strict_types=1);
namespace v0_9;

global $CONFIG;

use PHPUnit\Framework\TestCase;

use Skaut\HandbookAPI\v0_9\Exception\ConnectionException;

class ConnectionExceptionTest extends TestCase
{
    public function testCtor() : ConnectionException
    {
        $e = new ConnectionException(new \PDOException());
        $this->assertInstanceOf('\Skaut\HandbookAPI\v0_9\Exception\ConnectionException', $e);
        return $e;
    }

    /**
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
