<?php declare(strict_types=1);
namespace Skaut\HandbookAPI\v0_9\Exception;

@_API_EXEC === 1 or die('Restricted access.');

class SkautISAuthorizationException extends Exception
{
    const TYPE = 'SkautISAuthorizationException';
    const STATUS = 403;

    public function __construct()
    {
        parent::__construct('Insufficient SkautIS authorization');
    }
}
