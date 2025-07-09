<?php

declare(strict_types=1);

namespace Skaut\HandbookAPI\v1_0\Exception;

@_API_EXEC === 1 or exit('Restricted access.');

class FileUploadException extends Exception
{
    protected const TYPE = 'FileUploadException';

    protected const STATUS = 500;

    public function __construct(int $errorCode)
    {
        $message = 'Unknown error.';
        switch ($errorCode) {
            case UPLOAD_ERR_INI_SIZE:
            case UPLOAD_ERR_FORM_SIZE:
                $message = 'The uploaded file is too big.';
                break;
            case UPLOAD_ERR_PARTIAL:
            case UPLOAD_ERR_NO_FILE:
                $message = 'The uploaded file is corrupt.';
                break;
            case UPLOAD_ERR_NO_TMP_DIR:
            case UPLOAD_ERR_CANT_WRITE:
                $message = 'The server filesystem is misconfigured.';
                break;
        }
        parent::__construct($message);
    }
}
