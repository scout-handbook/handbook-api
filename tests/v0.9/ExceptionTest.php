<?php declare(strict_types=1);
namespace v0_9;

global $CONFIG;

use PHPUnit\Framework\TestCase;

class ExceptionTest extends TestCase
{
    /**
     * @covers Skaut\HandbookAPI\v0_9\Exception\Exception::__construct()
     */
    public function testCtor() : \Skaut\HandbookAPI\v0_9\Exception\Exception
    {
        $e = new \Skaut\HandbookAPI\v0_9\Exception\Exception('Emessage');
        $this->assertInstanceOf('\Skaut\HandbookAPI\v0_9\Exception\Exception', $e);
        return $e;
    }

    /**
     * @covers Skaut\HandbookAPI\v0_9\Exception\Exception::handle()
     * @depends testCtor
     */
    public function testHandle(\Skaut\HandbookAPI\v0_9\Exception\Exception $e) : void
    {
        $this->assertSame(['status' => 500, 'type' => 'Exception', 'message' => 'Emessage'], $e->handle());
    }
}
