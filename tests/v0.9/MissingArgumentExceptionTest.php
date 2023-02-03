<?php declare(strict_types=1);
namespace v0_9;

global $CONFIG;

use PHPUnit\Framework\TestCase;

use Skaut\HandbookAPI\v0_9\Exception\MissingArgumentException;

class MissingArgumentExceptionTest extends TestCase
{
    public function testCtorGet() : MissingArgumentException
    {
        $e = new MissingArgumentException(MissingArgumentException::GET, 'Gname');
        $this->assertInstanceOf('\Skaut\HandbookAPI\v0_9\Exception\MissingArgumentException', $e);
        return $e;
    }

    public function testCtorPost() : MissingArgumentException
    {
        $e = new MissingArgumentException(MissingArgumentException::POST, 'Pname');
        $this->assertInstanceOf('\Skaut\HandbookAPI\v0_9\Exception\MissingArgumentException', $e);
        return $e;
    }

    public function testCtorFile() : MissingArgumentException
    {
        $e = new MissingArgumentException(MissingArgumentException::FILE, 'Fname');
        $this->assertInstanceOf('\Skaut\HandbookAPI\v0_9\Exception\MissingArgumentException', $e);
        return $e;
    }

    /**
     * @depends testCtorGet
     */
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

    /**
     * @depends testCtorPost
     */
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

    /**
     * @depends testCtorFile
     */
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
