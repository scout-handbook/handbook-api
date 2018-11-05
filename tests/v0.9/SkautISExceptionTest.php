<?php declare(strict_types=1);
namespace v0_9;

global $CONFIG;
require_once('v0.9/internal/exceptions/SkautISException.php');

class SkautISExceptionTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @covers HandbookAPI\SkautISException::__construct()
     */
    public function testCtor() : \HandbookAPI\SkautISException
    {
        $e = new \HandbookAPI\SkautISException(new \Skautis\StaticClassException('Emessage'));
        $this->assertInstanceOf('\HandbookAPI\SkautISException', $e);
        return $e;
    }

    /**
     * @covers HandbookAPI\SkautISException::handle()
     * @depends testCtor
     */
    public function testHandle(\HandbookAPI\SkautISException $e) : void
    {
        $this->assertSame(
            ['status' => 403, 'type' => 'SkautISException', 'message' => 'SkautIS error: Emessage'],
            $e->handle()
        );
    }
}
