<?php declare(strict_types=1);
namespace Skaut\HandbookAPI\v0_9\Exception;

@_API_EXEC === 1 or die('Restricted access.');

class NotLockedException extends Exception
{
    const TYPE = 'NotLockedException';
    const STATUS = 412;

    public function __construct()
    {
        parent::__construct('This resource must be locked by a mutex for this operation.');
    }
}
