<?php declare(strict_types=1);
namespace v1_0;

global $CONFIG;

use PHPUnit\Framework\TestCase;

use Skaut\HandbookAPI\v1_0\Exception\RoleException;

class RoleExceptionTest extends TestCase
{
    /**
     * @covers Skaut\HandbookAPI\v1_0\Exception\RoleException::__construct()
     */
    public function testCtor() : RoleException
    {
        $e = new RoleException();
        $this->assertInstanceOf('\Skaut\HandbookAPI\v1_0\Exception\RoleException', $e);
        return $e;
    }

    /**
     * @covers Skaut\HandbookAPI\v1_0\Exception\RoleException::handle()
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
