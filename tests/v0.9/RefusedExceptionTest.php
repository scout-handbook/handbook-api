<?php declare(strict_types=1);
namespace v0_9;

global $CONFIG;
require_once('v0.9/internal/exceptions/RefusedException.php');

class RefusedExceptionTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @covers HandbookAPI\RefusedException::__construct()
     */
    public function testCtor() : \HandbookAPI\RefusedException
    {
        $e = new \HandbookAPI\RefusedException();
        $this->assertInstanceOf('\HandbookAPI\RefusedException', $e);
        return $e;
    }

    /**
     * @covers HandbookAPI\RefusedException::handle()
     * @depends testCtor
     */
    public function testHandle(\HandbookAPI\RefusedException $e) : void
    {
        $this->assertSame(
            ['status' => 403, 'type' => 'RefusedException', 'message' => 'Operation has been refused by the server.'],
            $e->handle()
        );
    }
}
