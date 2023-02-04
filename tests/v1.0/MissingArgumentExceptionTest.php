<?php declare(strict_types=1);
namespace v1_0;

global $CONFIG;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Depends;
use PHPUnit\Framework\TestCase;

use Skaut\HandbookAPI\v1_0\Exception\MissingArgumentException;

#[CoversClass(MissingArgumentException::class)]
class MissingArgumentExceptionTest extends TestCase
{
    public function testCtorGet() : MissingArgumentException
    {
        $e = new MissingArgumentException(MissingArgumentException::GET, 'Gname');
        $this->assertInstanceOf('\Skaut\HandbookAPI\v1_0\Exception\MissingArgumentException', $e);
        return $e;
    }

    public function testCtorPost() : MissingArgumentException
    {
        $e = new MissingArgumentException(MissingArgumentException::POST, 'Pname');
        $this->assertInstanceOf('\Skaut\HandbookAPI\v1_0\Exception\MissingArgumentException', $e);
        return $e;
    }

    public function testCtorFile() : MissingArgumentException
    {
        $e = new MissingArgumentException(MissingArgumentException::FILE, 'Fname');
        $this->assertInstanceOf('\Skaut\HandbookAPI\v1_0\Exception\MissingArgumentException', $e);
        return $e;
    }

    #[Depends("testCtorGet")]
    public function testHandleGet(MissingArgumentException $e) : void
    {
        $this->assertSame(
            [
                'status' => 400,
                'type' => 'MissingArgumentException',
                'message' => 'GET argument "Gname" must be provided.'
            ],
            $e->handle()
        );
    }

    #[Depends("testCtorPost")]
    public function testHandlePost(MissingArgumentException $e) : void
    {
        $this->assertSame(
            [
                'status' => 400,
                'type' => 'MissingArgumentException',
                'message' => 'POST argument "Pname" must be provided.'
            ],
            $e->handle()
        );
    }

    #[Depends("testCtorFile")]
    public function testHandleFile(MissingArgumentException $e) : void
    {
        $this->assertSame(
            [
                'status' => 400,
                'type' => 'MissingArgumentException',
                'message' => 'FILE argument "Fname" must be provided.'
            ],
            $e->handle()
        );
    }
}
