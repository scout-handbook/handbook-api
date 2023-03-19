<?php

declare(strict_types=1);

namespace Skaut\HandbookAPI\v1_0\Exception;

@_API_EXEC === 1 or die('Restricted access.');

class NotLockedException extends Exception
{
    protected const TYPE = 'NotLockedException';
    protected const STATUS = 412;

    public function __construct()
    {
        parent::__construct('This resource must be locked by a mutex for this operation.');
    }
}
