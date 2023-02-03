<?php declare(strict_types=1);
namespace v1_0;

global $CONFIG;

use PHPUnit\Framework\TestCase;

class ExceptionTest extends TestCase
{
    public function testHandle() : void
    {
        $e = new \Skaut\HandbookAPI\v1_0\Exception\Exception('Emessage');
        $this->assertSame(['status' => 500, 'type' => 'Exception', 'message' => 'Emessage'], $e->handle());
    }
}
