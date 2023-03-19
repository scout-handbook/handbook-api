<?php

declare(strict_types=1);

namespace Skaut\HandbookAPI\v1_0\Exception;

@_API_EXEC === 1 or die('Restricted access.');

class NotImplementedException extends Exception
{
    protected const TYPE = 'NotImplementedException';
    protected const STATUS = 501;

    public function __construct()
    {
        parent::__construct('The requested feature has not been implemented.');
    }
}
