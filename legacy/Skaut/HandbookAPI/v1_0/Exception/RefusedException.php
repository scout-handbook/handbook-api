<?php

declare(strict_types=1);

namespace Skaut\HandbookAPI\v1_0\Exception;

@_API_EXEC === 1 or exit('Restricted access.');

class RefusedException extends Exception
{
    protected const TYPE = 'RefusedException';

    protected const STATUS = 403;

    public function __construct()
    {
        parent::__construct('Operation has been refused by the server.');
    }
}
