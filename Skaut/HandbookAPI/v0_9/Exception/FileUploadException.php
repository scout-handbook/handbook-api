<?php declare(strict_types=1);
namespace Skaut\HandbookAPI\v0_9\Exception;

@_API_EXEC === 1 or die('Restricted access.');

class FileUploadException extends Exception
{
    const TYPE = 'FileUploadException';
    const STATUS = 500;

    public function __construct(int $errorCode)
    {
        switch($errorCode) {
            case UPLOAD_ERR_INI_SIZE:
            case UPLOAD_ERR_FORM_SIZE:
                $message = 'The uploaded file is too big.';
                break;
            case UPLOAD_ERR_PARTIAL:
            case UPLOAD_ERR_NO_FILE:
                $message = 'The uploaded file is corrupt.';
            case UPLOAD_ERR_NO_TMP_DIR:
            case UPLOAD_ERR_CANT_WRITE:
                $message = 'The server filesystem is misconfigured.';
            case UPLOAD_ERR_OK:
            case UPLOAD_ERR_EXTENSION:
                $message = 'Unknown error.';
        }
        parent::__construct($message);
    }
}
