<?php declare(strict_types=1);
namespace v0_9;

global $CONFIG;
require_once('v0.9/internal/exceptions/RoleException.php');

class RoleExceptionTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @covers HandbookAPI\RoleException::__construct()
     */
    public function testCtor() : \HandbookAPI\RoleException
    {
        $e = new \HandbookAPI\RoleException();
        $this->assertInstanceOf('\HandbookAPI\RoleException', $e);
        return $e;
    }

    /**
     * @covers HandbookAPI\RoleException::handle()
     * @depends testCtor
     */
    public function testHandle(\HandbookAPI\RoleException $e) : void
    {
        $this->assertSame(
            ['status' => 403, 'type' => 'RoleException', 'message' => 'You don\'t have permission for this action.'],
            $e->handle()
        );
    }
}
