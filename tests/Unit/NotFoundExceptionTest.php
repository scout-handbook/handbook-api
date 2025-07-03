<?php

declare(strict_types=1);

namespace v1_0;

global $CONFIG;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Depends;
use PHPUnit\Framework\TestCase;

use Skaut\HandbookAPI\v1_0\Exception\NotFoundException;

#[CoversClass(NotFoundException::class)]
class NotFoundExceptionTest extends TestCase
{
    public function testCtor(): NotFoundException
    {
        $e = new NotFoundException('Rname');
        $this->assertInstanceOf('\Skaut\HandbookAPI\v1_0\Exception\NotFoundException', $e);
        return $e;
    }

    #[Depends("testCtor")]
    public function testHandle(NotFoundException $e): void
    {
        $this->assertSame(
            ['status' => 404, 'type' => 'NotFoundException', 'message' => 'No such Rname has been found.'],
            $e->handle()
        );
    }
}
