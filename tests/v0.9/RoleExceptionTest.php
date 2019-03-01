<?php declare(strict_types=1);
namespace v0_9;

global $CONFIG;

use Skaut\HandbookAPI\v0_9\Exception\RoleException;

class RoleExceptionTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @covers Skaut\HandbookAPI\v0_9\Exception\RoleException::__construct()
     */
    public function testCtor() : RoleException
    {
        $e = new RoleException();
        $this->assertInstanceOf('\Skaut\HandbookAPI\v0_9\Exception\RoleException', $e);
        return $e;
    }

    /**
     * @covers Skaut\HandbookAPI\v0_9\Exception\RoleException::handle()
     * @depends testCtor
     */
    public function testHandle(RoleException $e) : void
    {
        $this->assertSame(
            ['status' => 403, 'type' => 'RoleException', 'message' => 'You don\'t have permission for this action.'],
            $e->handle()
        );
    }
}
