<?php

declare(strict_types=1);

namespace v1_0;

global $CONFIG;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Depends;
use PHPUnit\Framework\TestCase;
use Skaut\HandbookAPI\v1_0\Exception\RoleException;

#[CoversClass(RoleException::class)]
class RoleExceptionTest extends TestCase
{
    public function test_ctor(): RoleException
    {
        $e = new RoleException;
        $this->assertInstanceOf('\Skaut\HandbookAPI\v1_0\Exception\RoleException', $e);

        return $e;
    }

    #[Depends('test_ctor')]
    public function test_handle(RoleException $e): void
    {
        $this->assertSame(
            ['status' => 403, 'type' => 'RoleException', 'message' => 'You don\'t have permission for this action.'],
            $e->handle()
        );
    }
}
