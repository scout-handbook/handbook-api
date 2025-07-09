<?php

declare(strict_types=1);

namespace v1_0;

global $CONFIG;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Depends;
use PHPUnit\Framework\TestCase;
use Skaut\HandbookAPI\v1_0\Exception\FileUploadException;

/** @SuppressWarnings("PHPMD.TooManyPublicMethods") */
#[CoversClass(FileUploadException::class)]
class FileUploadExceptionTest extends TestCase
{
    public function test_ctor_ok(): FileUploadException
    {
        $e = new FileUploadException(UPLOAD_ERR_OK);
        $this->assertInstanceOf('\Skaut\HandbookAPI\v1_0\Exception\FileUploadException', $e);

        return $e;
    }

    public function test_ctor_ini_size(): FileUploadException
    {
        $e = new FileUploadException(UPLOAD_ERR_INI_SIZE);
        $this->assertInstanceOf('\Skaut\HandbookAPI\v1_0\Exception\FileUploadException', $e);

        return $e;
    }

    public function test_ctor_form_size(): FileUploadException
    {
        $e = new FileUploadException(UPLOAD_ERR_FORM_SIZE);
        $this->assertInstanceOf('\Skaut\HandbookAPI\v1_0\Exception\FileUploadException', $e);

        return $e;
    }

    public function test_ctor_partial(): FileUploadException
    {
        $e = new FileUploadException(UPLOAD_ERR_PARTIAL);
        $this->assertInstanceOf('\Skaut\HandbookAPI\v1_0\Exception\FileUploadException', $e);

        return $e;
    }

    public function test_ctor_no_file(): FileUploadException
    {
        $e = new FileUploadException(UPLOAD_ERR_NO_FILE);
        $this->assertInstanceOf('\Skaut\HandbookAPI\v1_0\Exception\FileUploadException', $e);

        return $e;
    }

    public function test_ctor_no_tmp_dir(): FileUploadException
    {
        $e = new FileUploadException(UPLOAD_ERR_NO_TMP_DIR);
        $this->assertInstanceOf('\Skaut\HandbookAPI\v1_0\Exception\FileUploadException', $e);

        return $e;
    }

    public function test_ctor_cant_write(): FileUploadException
    {
        $e = new FileUploadException(UPLOAD_ERR_CANT_WRITE);
        $this->assertInstanceOf('\Skaut\HandbookAPI\v1_0\Exception\FileUploadException', $e);

        return $e;
    }

    public function test_ctor_extension(): FileUploadException
    {
        $e = new FileUploadException(UPLOAD_ERR_EXTENSION);
        $this->assertInstanceOf('\Skaut\HandbookAPI\v1_0\Exception\FileUploadException', $e);

        return $e;
    }

    #[Depends('test_ctor_ok')]
    public function test_handle_ok(FileUploadException $e): void
    {
        $this->assertSame(
            ['status' => 500, 'type' => 'FileUploadException', 'message' => 'Unknown error.'],
            $e->handle()
        );
    }

    #[Depends('test_ctor_ini_size')]
    public function test_handle_ini_size(FileUploadException $e): void
    {
        $this->assertSame(
            ['status' => 500, 'type' => 'FileUploadException', 'message' => 'The uploaded file is too big.'],
            $e->handle()
        );
    }

    #[Depends('test_ctor_form_size')]
    public function test_handle_form_size(FileUploadException $e): void
    {
        $this->assertSame(
            ['status' => 500, 'type' => 'FileUploadException', 'message' => 'The uploaded file is too big.'],
            $e->handle()
        );
    }

    #[Depends('test_ctor_partial')]
    public function test_handle_partial(FileUploadException $e): void
    {
        $this->assertSame(
            ['status' => 500, 'type' => 'FileUploadException', 'message' => 'The uploaded file is corrupt.'],
            $e->handle()
        );
    }

    #[Depends('test_ctor_no_file')]
    public function test_handle_no_file(FileUploadException $e): void
    {
        $this->assertSame(
            ['status' => 500, 'type' => 'FileUploadException', 'message' => 'The uploaded file is corrupt.'],
            $e->handle()
        );
    }

    #[Depends('test_ctor_no_tmp_dir')]
    public function test_handle_no_tmp_dir(FileUploadException $e): void
    {
        $this->assertSame(
            ['status' => 500, 'type' => 'FileUploadException', 'message' => 'The server filesystem is misconfigured.'],
            $e->handle()
        );
    }

    #[Depends('test_ctor_cant_write')]
    public function test_handle_cant_write(FileUploadException $e): void
    {
        $this->assertSame(
            ['status' => 500, 'type' => 'FileUploadException', 'message' => 'The server filesystem is misconfigured.'],
            $e->handle()
        );
    }

    #[Depends('test_ctor_extension')]
    public function test_handle_extension(FileUploadException $e): void
    {
        $this->assertSame(
            ['status' => 500, 'type' => 'FileUploadException', 'message' => 'Unknown error.'],
            $e->handle()
        );
    }
}
