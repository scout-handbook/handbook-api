<?php

declare(strict_types=1);

namespace v1_0;

global $CONFIG;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Depends;
use PHPUnit\Framework\TestCase;
use Skautis\StaticClassException;

use Skaut\HandbookAPI\v1_0\Exception\SkautISException;

#[CoversClass(SkautISException::class)]
class SkautISExceptionTest extends TestCase
{
    public function testCtor(): SkautISException
    {
        $e = new SkautISException(new StaticClassException('Emessage'));
        $this->assertInstanceOf('\Skaut\HandbookAPI\v1_0\Exception\SkautISException', $e);
        return $e;
    }

    #[Depends("testCtor")]
    public function testHandle(SkautISException $e): void
    {
        $this->assertSame(
            ['status' => 403, 'type' => 'SkautISException', 'message' => 'SkautIS error: Emessage'],
            $e->handle()
        );
    }
}
