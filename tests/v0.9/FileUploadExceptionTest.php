<?php declare(strict_types=1);
namespace v0_9;

global $CONFIG;

use PHPUnit\Framework\TestCase;

use Skaut\HandbookAPI\v0_9\Exception\FileUploadException;

/** @SuppressWarnings(PHPMD.TooManyPublicMethods) */
class FileUploadExceptionTest extends TestCase
{
    /**
     * @covers Skaut\HandbookAPI\v0_9\Exception\FileUploadException::__construct()
     */
    public function testCtorOk() : FileUploadException
    {
        $e = new FileUploadException(UPLOAD_ERR_OK);
        $this->assertInstanceOf('\Skaut\HandbookAPI\v0_9\Exception\FileUploadException', $e);
        return $e;
    }

    /**
     * @covers Skaut\HandbookAPI\v0_9\Exception\FileUploadException::__construct()
     */
    public function testCtorIniSize() : FileUploadException
    {
        $e = new FileUploadException(UPLOAD_ERR_INI_SIZE);
        $this->assertInstanceOf('\Skaut\HandbookAPI\v0_9\Exception\FileUploadException', $e);
        return $e;
    }

    /**
     * @covers Skaut\HandbookAPI\v0_9\Exception\FileUploadException::__construct()
     */
    public function testCtorFormSize() : FileUploadException
    {
        $e = new FileUploadException(UPLOAD_ERR_FORM_SIZE);
        $this->assertInstanceOf('\Skaut\HandbookAPI\v0_9\Exception\FileUploadException', $e);
        return $e;
    }

    /**
     * @covers Skaut\HandbookAPI\v0_9\Exception\FileUploadException::__construct()
     */
    public function testCtorPartial() : FileUploadException
    {
        $e = new FileUploadException(UPLOAD_ERR_PARTIAL);
        $this->assertInstanceOf('\Skaut\HandbookAPI\v0_9\Exception\FileUploadException', $e);
        return $e;
    }

    /**
     * @covers Skaut\HandbookAPI\v0_9\Exception\FileUploadException::__construct()
     */
    public function testCtorNoFile() : FileUploadException
    {
        $e = new FileUploadException(UPLOAD_ERR_NO_FILE);
        $this->assertInstanceOf('\Skaut\HandbookAPI\v0_9\Exception\FileUploadException', $e);
        return $e;
    }

    /**
     * @covers Skaut\HandbookAPI\v0_9\Exception\FileUploadException::__construct()
     */
    public function testCtorNoTmpDir() : FileUploadException
    {
        $e = new FileUploadException(UPLOAD_ERR_NO_TMP_DIR);
        $this->assertInstanceOf('\Skaut\HandbookAPI\v0_9\Exception\FileUploadException', $e);
        return $e;
    }

    /**
     * @covers Skaut\HandbookAPI\v0_9\Exception\FileUploadException::__construct()
     */
    public function testCtorCantWrite() : FileUploadException
    {
        $e = new FileUploadException(UPLOAD_ERR_CANT_WRITE);
        $this->assertInstanceOf('\Skaut\HandbookAPI\v0_9\Exception\FileUploadException', $e);
        return $e;
    }

    /**
     * @covers Skaut\HandbookAPI\v0_9\Exception\FileUploadException::__construct()
     */
    public function testCtorExtension() : FileUploadException
    {
        $e = new FileUploadException(UPLOAD_ERR_EXTENSION);
        $this->assertInstanceOf('\Skaut\HandbookAPI\v0_9\Exception\FileUploadException', $e);
        return $e;
    }

    /**
     * @covers Skaut\HandbookAPI\v0_9\Exception\FileUploadException::handle()
     * @depends testCtorOk
     */
    public function testHandleOk(FileUploadException $e) : void
    {
        $this->assertSame(
            ['status' => 500, 'type' => 'FileUploadException', 'message' => 'Unknown error.'],
            $e->handle()
        );
    }

    /**
     * @covers Skaut\HandbookAPI\v0_9\Exception\FileUploadException::handle()
     * @depends testCtorIniSize
     */
    public function testHandleIniSize(FileUploadException $e) : void
    {
        $this->assertSame(
            ['status' => 500, 'type' => 'FileUploadException', 'message' => 'The uploaded file is too big.'],
            $e->handle()
        );
    }

    /**
     * @covers Skaut\HandbookAPI\v0_9\Exception\FileUploadException::handle()
     * @depends testCtorFormSize
     */
    public function testHandleFormSize(FileUploadException $e) : void
    {
        $this->assertSame(
            ['status' => 500, 'type' => 'FileUploadException', 'message' => 'The uploaded file is too big.'],
            $e->handle()
        );
    }

    /**
     * @covers Skaut\HandbookAPI\v0_9\Exception\FileUploadException::handle()
     * @depends testCtorPartial
     */
    public function testHandlePartial(FileUploadException $e) : void
    {
        $this->assertSame(
            ['status' => 500, 'type' => 'FileUploadException', 'message' => 'The uploaded file is corrupt.'],
            $e->handle()
        );
    }

    /**
     * @covers Skaut\HandbookAPI\v0_9\Exception\FileUploadException::handle()
     * @depends testCtorNoFile
     */
    public function testHandleNoFile(FileUploadException $e) : void
    {
        $this->assertSame(
            ['status' => 500, 'type' => 'FileUploadException', 'message' => 'The uploaded file is corrupt.'],
            $e->handle()
        );
    }

    /**
     * @covers Skaut\HandbookAPI\v0_9\Exception\FileUploadException::handle()
     * @depends testCtorNoTmpDir
     */
    public function testHandleNoTmpDir(FileUploadException $e) : void
    {
        $this->assertSame(
            ['status' => 500, 'type' => 'FileUploadException', 'message' => 'The server filesystem is misconfigured.'],
            $e->handle()
        );
    }

    /**
     * @covers Skaut\HandbookAPI\v0_9\Exception\FileUploadException::handle()
     * @depends testCtorCantWrite
     */
    public function testHandleCantWrite(FileUploadException $e) : void
    {
        $this->assertSame(
            ['status' => 500, 'type' => 'FileUploadException', 'message' => 'The server filesystem is misconfigured.'],
            $e->handle()
        );
    }

    /**
     * @covers Skaut\HandbookAPI\v0_9\Exception\FileUploadException::handle()
     * @depends testCtorExtension
     */
    public function testHandleExtension(FileUploadException $e) : void
    {
        $this->assertSame(
            ['status' => 500, 'type' => 'FileUploadException', 'message' => 'Unknown error.'],
            $e->handle()
        );
    }
}
