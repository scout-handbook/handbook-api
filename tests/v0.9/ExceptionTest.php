<?php declare(strict_types=1);
namespace v0_9;

global $CONFIG;
require_once('v0.9/internal/exceptions/Exception.php');

class ExceptionTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @covers HandbookAPI\Exception::__construct()
     */
    public function testCtor() : \HandbookAPI\Exception
    {
        $e = new \HandbookAPI\Exception('Emessage');
        $this->assertInstanceOf('\HandbookAPI\Exception', $e);
        return $e;
    }

    /**
     * @covers HandbookAPI\Exception::handle()
     * @depends testCtor
     */
    public function testHandle(\HandbookAPI\Exception $e) : void
    {
        $this->assertSame(['status' => 500, 'type' => 'Exception', 'message' => 'Emessage'], $e->handle());
    }
}
