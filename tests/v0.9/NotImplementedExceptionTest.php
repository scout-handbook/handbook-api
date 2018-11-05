<?php declare(strict_types=1);
namespace v0_9;

global $CONFIG;
require_once('v0.9/internal/exceptions/NotImplementedException.php');

class NotImplementedExceptionTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @covers HandbookAPI\NotImplementedException::__construct()
     */
    public function testCtor() : \HandbookAPI\NotImplementedException
    {
        $e = new \HandbookAPI\NotImplementedException();
        $this->assertInstanceOf('\HandbookAPI\NotImplementedException', $e);
        return $e;
    }

    /**
     * @covers HandbookAPI\NotImplementedException::handle()
     * @depends testCtor
     */
    public function testHandle(\HandbookAPI\NotImplementedException $e) : void
    {
        $this->assertSame(
            [
                'status' => 501,
                'type' => 'NotImplementedException',
                'message' => 'The requested feature has not been implemented.'
            ],
            $e->handle()
        );
    }
}
