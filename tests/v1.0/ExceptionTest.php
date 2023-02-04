<?php declare(strict_types=1);
namespace v1_0;

global $CONFIG;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

use Skaut\HandbookAPI\v1_0\Exception\Exception as HandbookException;

#[CoversClass(HandbookException::class)]
class ExceptionTest extends TestCase
{
    public function testHandle() : void
    {
        $e = new HandbookException('Emessage');
        $this->assertSame(['status' => 500, 'type' => 'Exception', 'message' => 'Emessage'], $e->handle());
    }
}
