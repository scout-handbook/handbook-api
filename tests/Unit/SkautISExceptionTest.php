<?php

declare(strict_types=1);

namespace v1_0;

global $CONFIG;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Depends;
use PHPUnit\Framework\TestCase;
use Skaut\HandbookAPI\v1_0\Exception\SkautISException;
use Skautis\StaticClassException;

#[CoversClass(SkautISException::class)]
class SkautISExceptionTest extends TestCase
{
    public function test_ctor(): SkautISException
    {
        $e = new SkautISException(new StaticClassException('Emessage'));
        $this->assertInstanceOf('\Skaut\HandbookAPI\v1_0\Exception\SkautISException', $e);

        return $e;
    }

    #[Depends('test_ctor')]
    public function test_handle(SkautISException $e): void
    {
        $this->assertSame(
            ['status' => 403, 'type' => 'SkautISException', 'message' => 'SkautIS error: Emessage'],
            $e->handle()
        );
    }
}
