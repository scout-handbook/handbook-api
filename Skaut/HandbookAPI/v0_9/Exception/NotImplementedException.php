<?php declare(strict_types=1);
namespace Skaut\HandbookAPI\v0_9\Exception;

@_API_EXEC === 1 or die('Restricted access.');

class NotImplementedException extends Exception
{
    const TYPE = 'NotImplementedException';
    const STATUS = 501;

    public function __construct()
    {
        parent::__construct('The requested feature has not been implemented.');
    }
}
