<?php declare(strict_types=1);
namespace v0_9;

global $CONFIG;
require_once('v0.9/internal/exceptions/ConnectionException.php');

class ConnectionExceptionTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @covers HandbookAPI\ConnectionException::__construct()
     */
    public function testCtor() : \HandbookAPI\ConnectionException
    {
        $e = new \HandbookAPI\ConnectionException(new \PDOException());
        $this->assertInstanceOf('\HandbookAPI\ConnectionException', $e);
        return $e;
    }

    /**
     * @covers HandbookAPI\ConnectionException::handle()
     * @depends testCtor
     */
    public function testHandle(\HandbookAPI\ConnectionException $e) : void
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
