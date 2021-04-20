<?php declare(strict_types=1);
namespace v1_0;

global $CONFIG;

use PHPUnit\Framework\TestCase;
use Skautis\StaticClassException;

use Skaut\HandbookAPI\v1_0\Exception\SkautISException;

class SkautISExceptionTest extends TestCase
{
    /**
     * @covers Skaut\HandbookAPI\v1_0\Exception\SkautISException::__construct()
     */
    public function testCtor() : SkautISException
    {
        $e = new SkautISException(new StaticClassException('Emessage'));
        $this->assertInstanceOf('\Skaut\HandbookAPI\v1_0\Exception\SkautISException', $e);
        return $e;
    }

    /**
     * @covers Skaut\HandbookAPI\v1_0\Exception\SkautISException::handle()
     * @depends testCtor
     */
    public function testHandle(SkautISException $e) : void
    {
        $this->assertSame(
            ['status' => 403, 'type' => 'SkautISException', 'message' => 'SkautIS error: Emessage'],
            $e->handle()
        );
    }
}
