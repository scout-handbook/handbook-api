<?php declare(strict_types=1);
namespace v0_9;

global $CONFIG;

use PHPUnit\Framework\TestCase;

use Skaut\HandbookAPI\v0_9\Exception\NotImplementedException;

class NotImplementedExceptionTest extends TestCase
{
    public function testCtor() : NotImplementedException
    {
        $e = new NotImplementedException();
        $this->assertInstanceOf('\Skaut\HandbookAPI\v0_9\Exception\NotImplementedException', $e);
        return $e;
    }

    /**
     * @depends testCtor
     */
    public function testHandle(NotImplementedException $e) : void
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
