<?php declare(strict_types=1);
namespace v0_9;

global $CONFIG;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Depends;
use PHPUnit\Framework\TestCase;

use Skaut\HandbookAPI\v0_9\Exception\SkautISAuthorizationException;

#[CoversClass(SkautISAuthorizationException::class)]
class SkautISAuthorizationExceptionTest extends TestCase
{
    public function testCtor() : SkautISAuthorizationException
    {
        $e = new SkautISAuthorizationException();
        $this->assertInstanceOf('\Skaut\HandbookAPI\v0_9\Exception\SkautISAuthorizationException', $e);
        return $e;
    }

    #[Depends("testCtor")]
    public function testHandle(SkautISAuthorizationException $e) : void
    {
        $this->assertSame(
            [
                'status' => 403,
                'type' => 'SkautISAuthorizationException',
                'message' => 'Insufficient SkautIS authorization'
            ],
            $e->handle()
        );
    }
}
