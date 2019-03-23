<?php declare(strict_types=1);
namespace Skaut\HandbookAPI\v1_0\Exception;

@_API_EXEC === 1 or die('Restricted access.');

class AuthenticationException extends Exception
{
    const TYPE = 'AuthenticationException';
    const STATUS = 403;

    public function __construct()
    {
        parent::__construct('Authentication failed.');
    }
}
